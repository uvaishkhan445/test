from flask import Flask, request, jsonify, render_template, url_for
from flask_mysqldb import MySQL
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

# MySQL configurations
app.config["MYSQL_HOST"] = "localhost"
app.config["MYSQL_USER"] = "root"
app.config["MYSQL_PASSWORD"] = ""
app.config["MYSQL_DB"] = "oyly"

mysql = MySQL(app)


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


if __name__ == "__main__":
    app.run(debug=True)
