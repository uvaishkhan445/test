from flask import (
    Flask,
    request,
    jsonify,
    render_template,
    redirect,
    url_for,
    Response,
    make_response,
)
from flask_mysqldb import MySQL
from flask_cors import CORS
from flask_mail import Mail, Message
import pandas as pd
from fpdf import FPDF

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


class PDF(FPDF):
    def header(self):
        self.set_font("Arial", "B", 12)
        self.cell(0, 10, "Sample PDF Document", 0, 1, "C")

    def footer(self):
        self.set_y(-15)
        self.set_font("Arial", "I", 8)
        self.cell(0, 10, f"Page {self.page_no()}", 0, 0, "C")

    def chapter_title(self, title):
        self.set_font("Arial", "B", 12)
        self.cell(0, 10, title, 0, 1, "L")
        self.ln(10)

    def chapter_body(self, body):
        self.set_font("Arial", "", 12)
        self.multi_cell(0, 10, body)
        self.ln()


@app.route("/download_pdf", methods=["GET"])
def download_pdf():
    pdf = PDF()
    pdf.add_page()
    pdf.chapter_title("Chapter 1: Introduction")
    pdf.chapter_body(
        "This is a simple chapter body to demonstrate how to use FPDF in Flask."
    )
    pdf.chapter_title("Chapter 2: More Content")
    pdf.chapter_body(
        "Here is some more content for the second chapter of our PDF document."
    )

    response = make_response(pdf.output(dest="S").encode("latin1"))
    response.headers["Content-Type"] = "application/pdf"
    # response.headers["Content-Disposition"] = "inline; filename=output.pdf"
    response.headers["Content-Disposition"] = "attachment; filename=output.pdf"
    return response


if __name__ == "__main__":
    app.run(debug=True)
