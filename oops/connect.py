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
