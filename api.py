from flask import Flask, request, jsonify
from flask_mysqldb import MySQL

app = Flask(__name__)

# MySQL configurations
app.config["MYSQL_HOST"] = "localhost"
app.config["MYSQL_USER"] = "root"
app.config["MYSQL_PASSWORD"] = ""
app.config["MYSQL_DB"] = "oyly"

mysql = MySQL(app)


@app.route("/")
def index():
    return "Welcome to the Flask MySQL CRUD API"


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
    cur.close()
    return jsonify({"message": "User added successfully!"}), 201


# Get all users
@app.route("/users", methods=["GET"])
def get_users():
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM contact_us")
    # Fetch all rows from the executed query
    columns = [desc[0] for desc in cur.description]  # Get column names
    rows = cur.fetchall()
    data = {
        row[0]: dict(zip(columns[1:], row[1:])) for row in rows
    }  # Convert rows to key-value pairs

    cur.close()
    # users_list = []
    # for user in users:
    #     user_dict = {
    #         "id": user[0],
    #         "admin_id": user[1],
    #         "name": user[2],
    #         "mobile_no": user[3],
    #         "email_id": user[4],
    #         "message": user[5],
    #         "date": user[6],
    #         "status": user[7],
    #     }
    #     users_list.append(user_dict)

    return jsonify(data)
    # return jsonify(users)


# Get a single user by id
@app.route("/users/<int:id>", methods=["GET"])
def get_user(id):
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM contact_us WHERE id = %s", (id,))
    user = cur.fetchone()
    cur.close()
    if user:
        return jsonify(user)
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
    return jsonify({"message": "User updated successfully!"})


# Delete a user by id
@app.route("/users/<int:id>", methods=["DELETE"])
def delete_user(id):
    cur = mysql.connection.cursor()
    cur.execute("DELETE FROM contact_us WHERE id = %s", (id,))
    mysql.connection.commit()
    cur.close()
    return jsonify({"message": "User deleted successfully!"})


if __name__ == "__main__":
    app.run(debug=True)
