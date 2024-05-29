import connect


def getAllData():
    connect.cursor
    query = "SELECT * FROM contact_us"
    connect.cursor.execute(query)
    result = connect.cursor.fetchall()
    return result
