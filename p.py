# importing required library
import json
import mysql.connector
from datetime import date, datetime


# Custom JSONEncoder subclass
class CustomEncoder(json.JSONEncoder):
    def default(self, obj):
        if isinstance(obj, (date, datetime)):
            return obj.isoformat()
        return super().default(obj)


# connecting to the database
dataBase = mysql.connector.connect(
    host="localhost", user="root", passwd="", database="oyly"
)

# preparing a cursor object
cursorObject = dataBase.cursor(dictionary=True)

print("Displaying NAME and ROLL columns from the STUDENT table:")

# selecting query
query = "SELECT * FROM contact_us"
cursorObject.execute(query)

myresult = cursorObject.fetchall()
json_string = json.dumps(myresult, cls=CustomEncoder, indent=4)

print(json_string)
# for x in myresult:
#     print(x)

# disconnecting from server
dataBase.close()
