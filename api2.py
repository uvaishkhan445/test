from flask import Flask, request, jsonify, render_template, redirect, url_for, Response
from flask_mysqldb import MySQL
from flask_cors import CORS
from flask_mail import Mail, Message
import pandas as pd

# from weasyprint import HTML

app = Flask(__name__)
CORS(app)

# MySQL configurations
app.config["MYSQL_HOST"] = "localhost"
app.config["MYSQL_USER"] = "root"
app.config["MYSQL_PASSWORD"] = ""
app.config["MYSQL_DB"] = "oyly"

mysql = MySQL(app)


# Configure Flask-Mail settings
app.config["MAIL_SERVER"] = "smtp.gmail.com"  # SMTP server address
app.config["MAIL_PORT"] = 587  # Port
app.config["MAIL_USE_TLS"] = True  # TLS
app.config["MAIL_USERNAME"] = "uvaishkhan20@gmail.com"  # Your email username
app.config["MAIL_PASSWORD"] = "ehmwuqeutocbvlgy"  # Your email password

mail = Mail(app)


@app.route("/")
def index():
    return render_template("index.html")


# Create a new user
@app.route("/users", methods=["POST"])
def add_user():
    data = request.get_json()
    admin_id = data["admin_id"]
    name = data["name"]
    mobile_no = data["mobile_no"]
    email_id = data["email_id"]
    message = data["message"]
    date = data["date"]
    status = data["status"]
    cur = mysql.connection.cursor()
    cur.execute(
        "INSERT INTO contact_us (admin_id,name, mobile_no,email_id,message,date,status) VALUES (%s, %s,%s,%s,%s,%s,%s)",
        (admin_id, name, mobile_no, email_id, message, date, status),
    )
    mysql.connection.commit()
    user_id = cur.lastrowid
    cur.close()
    return jsonify({"id": user_id, "name": name}), 201


# Get all users
@app.route("/users", methods=["GET"])
def get_users():
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM contact_us order by id desc")
    users = cur.fetchall()
    cur.close()

    users_list = []
    for user in users:
        user_dict = {
            "id": user[0],
            "admin_id": user[1],
            "name": user[2],
            "mobile_no": user[3],
            "email_id": user[4],
            "message": user[5],
            "date": user[6],
            "status": user[7],
        }
        users_list.append(user_dict)

    return jsonify(users_list)


# Get a single user by id
@app.route("/users/<int:id>", methods=["GET"])
def get_user(id):
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM contact_us WHERE id = %s", (id,))
    user = cur.fetchone()
    cur.close()
    if user:
        user_dict = {"id": user[0], "name": user[1], "email": user[2]}
        return jsonify(user_dict)
    else:
        return jsonify({"message": "User not found!"}), 404


# Update a user by id
@app.route("/users/<int:id>", methods=["PUT"])
def update_user(id):
    data = request.get_json()
    name = data["name"]
    email = data["email"]
    cur = mysql.connection.cursor()
    cur.execute(
        "UPDATE contact_us SET name = %s, email = %s WHERE id = %s", (name, email, id)
    )
    mysql.connection.commit()
    cur.close()
    return jsonify(
        {
            "id": id,
            "name": name,
            "email": email,
            "message": "User updated successfully!",
        }
    )


# Delete a user by id
@app.route("/users/<int:id>", methods=["DELETE"])
def delete_user(id):
    cur = mysql.connection.cursor()
    cur.execute("DELETE FROM contact_us WHERE id = %s", (id,))
    mysql.connection.commit()
    cur.close()
    return jsonify({"id": id, "message": "User deleted successfully!"})


# send mail notification


@app.route("/send_email", methods=["GET"])
def send_email():
    # Create message object
    msg = Message(
        subject="Hello from Flask",
        sender="uvaishkhan20@gmail.com",
        recipients=["kuvaish103@gmail.com"],
    )
    msg.body = "This is a test email sent from Flask using Flask-Mail."

    # Send the email
    mail.send(msg)
    return "Email sent successfully!"


# download data in csv format
@app.route("/download_csv", methods=["GET"])
def download_csv():
    # Create some sample data

    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM contact_us order by id desc")
    users = cur.fetchall()
    cur.close()
    data = [
        {
            "id": user[0],
            "admin_id": user[1],
            "name": user[2],
            "mobile_no": user[3],
            "email_id": user[4],
            "message": user[5],
            "date": user[6],
            "status": user[7],
        }
        for user in users
    ]
    df = pd.DataFrame(data)

    # Convert DataFrame to CSV
    csv_data = df.to_csv(index=False)

    # Create a response object
    response = Response(csv_data, mimetype="text/csv")
    response.headers.set("Content-Disposition", "attachment", filename="data.csv")

    return response


# download PDF file
@app.route("/download_pdf", methods=["GET"])
def download_pdf():
    # Sample data
    data = [
        {"name": "Alice", "age": 24, "city": "New York"},
        {"name": "Bob", "age": 27, "city": "San Francisco"},
        {"name": "Charlie", "age": 22, "city": "Los Angeles"},
    ]

    # Render HTML template with data
    html_content = render_template_string(
        """
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PDF Report</title>
        <style>
            body { font-family: Arial, sans-serif; }
            h1 { color: #333; }
            table { width: 100%; border-collapse: collapse; }
            th, td { padding: 8px 12px; border: 1px solid #ccc; }
            th { background-color: #f4f4f4; }
        </style>
    </head>
    <body>
        <h1>Sample PDF Report</h1>
        <table>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>City</th>
            </tr>
            {% for row in data %}
            <tr>
                <td>{{ row.name }}</td>
                <td>{{ row.age }}</td>
                <td>{{ row.city }}</td>
            </tr>
            {% endfor %}
        </table>
    </body>
    </html>
    """,
        data=data,
    )

    # Convert HTML content to PDF
    pdf = HTML(string=html_content).write_pdf()

    # Create a response object
    response = make_response(pdf)
    response.headers["Content-Type"] = "application/pdf"
    response.headers["Content-Disposition"] = "attachment; filename=report.pdf"

    return response


if __name__ == "__main__":
    app.run(debug=True)
