import connect

connect.myconnect(
    host="localhost",
    user="root",
    password="",
    database="oyly",
)
print(connect.getAllData())
