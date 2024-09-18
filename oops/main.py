import connect
import getModal

connect.myconnect(
    host="localhost",
    user="root",
    password="",
    database="oyly",
)
data = getModal.getAllData()
for row in data:
    print(row)
