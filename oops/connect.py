import mysql.connector


def myconnect(host, user, password, database):
    global cursor
    global connection
    connection = mysql.connector.connect(
        host=host,
        user=user,
        password=password,
        database=database,
    )
    cursor = connection.cursor()


def getAllData():
    global cursor
    query = "SELECT * FROM contact_us"
    cursor.execute(query)
    result = cursor.fetchall()
    return result
