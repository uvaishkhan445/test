# importing required library
import json
import mysql.connector

# connecting to the database
dataBase = mysql.connector.connect(
    host="localhost", user="root", passwd="", database="oyly"
)

# preparing a cursor object
cursorObject = dataBase.cursor(dictionary=True)

print("Displaying NAME and ROLL columns from the STUDENT table:")

# selecting query
query = "SELECT id,name,mobile_no,email_id,message,status FROM contact_us"
cursorObject.execute(query)

myresult = cursorObject.fetchall()
json_string = json.dumps(myresult, indent=4)

print(json_string)
# for x in myresult:
#     print(x)

# disconnecting from server
dataBase.close()
