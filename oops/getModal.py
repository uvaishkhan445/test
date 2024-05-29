import connect


def getAllData():
    conn = connect.cursor
    query = "SELECT * FROM contact_us"
    conn.execute(query)
    result = conn.fetchall()
    return result
