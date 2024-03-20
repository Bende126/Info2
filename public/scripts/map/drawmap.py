#!/usr/bin/env python3
import mysql.connector
mydb = mysql.connector.connect(
    host = "localhost",
    user = "labor",
    password = "asdf1234",
    database = "mav",
    auth_plugin='mysql_native_password'
)

def allm():

    mycursor = mydb.cursor()

    query = "select allomas1.Coord_x as 'PointA_x', allomas1.Coord_y as 'PointA_y', allomas2.Coord_x as 'PointB_x', allomas2.Coord_y as 'PointB_y', utvonal.ID from utvonal inner join allomas allomas1 on utvonal.PointA = allomas1.ID inner join allomas allomas2 on utvonal.PointB = allomas2.ID;"
    mycursor.execute(query)
    endpoints = mycursor.fetchall() # tuple ami pointax pointay pointbx pointby id

    query = "select Nev, Coord_x, Coord_y from allomas;"
    mycursor.execute(query)
    points = mycursor.fetchall() # tuple ami nev coord_x coord_y

    print('<svg width="1000" height="1000">')

    for x in endpoints:
        query = f"select alu.*, allomas1.* from utvonal inner join allomas_utvonal alu on alu.UtvonalID = utvonal.ID inner join allomas allomas1 on allomas1.ID = alu.AllomasID where utvonal.id = '{x[4]}';"
        mycursor.execute(query)
        btwpoints = mycursor.fetchall() #tuple ami ID AllomasID UtvonalID ID Nev Coord_x Coord_y
        filler = ""
        for y in btwpoints:
            filler = filler + f"{y[5]*10},{y[6]*10} "

        print(f'<polyline points="{x[0]*10},{x[1]*10} {filler}{x[2]*10},{x[3]*10}" fill="none" style="stroke: orange; stroke-width: 1;" />')

    for index,z in enumerate(points):
        print(f'<circle cx="{z[1]*10}" cy="{z[2]*10}" r="4" fill="black"/>')
        print(f'<text x="{(z[1]*10)-30}" y="{(z[2]*10)+(15*(-1**index))}" fill="black">{z[0]}</text>')

    print('</svg>')

def specific(mid):

    mycursor = mydb.cursor()

    query = f"""select allomas1.Coord_x as 'PointA_x', allomas1.Coord_y as 'PointA_y', allomas1.Nev as 'PointA_Nev', allomas2.Coord_x as 'PointB_x', allomas2.Coord_y as 'PointB_y', allomas2.Nev as 'PointB_Nev', utvonal.ID
            from utvonal
            inner join allomas allomas1 on utvonal.PointA = allomas1.ID
            inner join allomas allomas2 on utvonal.PointB = allomas2.ID
            where utvonal.ID = '{mid}';"""

    mycursor.execute(query)
    endpoints = mycursor.fetchall() # tuple ami pointax pointay pointanev pointbx pointby pointbnev utid utnev

    query = f"""select alu.*, allomas1.* from utvonal
            inner join allomas_utvonal alu on alu.UtvonalID = utvonal.ID
            inner join allomas allomas1 on allomas1.ID = alu.AllomasID
            where utvonal.id = '{mid}';"""

    mycursor.execute(query)
    btwpoints = mycursor.fetchall() #tuple ami ID AllomasID UtvonalID ID Nev Coord_x Coord_y

    print(f'<svg width="300" height="300" id="{endpoints[0][6]}">')
    
    if mycursor.rowcount >1:
        filler=""
        for x in btwpoints:
            filler = filler + f"{x[5] *3},{x[6] *3} "
    else:
        filler = f"{btwpoints[0][5] *3},{btwpoints[0][6] *3} "
    
    print(f'<polyline points="{endpoints[0][0] *3},{endpoints[0][1] *3} {filler}{endpoints[0][3] *3},{endpoints[0][4] *3}" fill="none" style="stroke: orange; stroke-width: 1;" />')

    for z in btwpoints:
        print(f'<circle cx="{z[5] *3}" cy="{z[6] *3}" r="4" fill="black"/>')
        print(f'<text x="{(z[5] *3)-30}" y="{(z[6] *3)+(15)}" fill="black">{z[4]}</text>')

    #pointa
    print(f'<circle cx="{endpoints[0][0] *3}" cy="{endpoints[0][1] *3}" r="4" fill="black"/>')
    print(f'<text x="{(endpoints[0][0] *3)-30}" y="{(endpoints[0][1] *3)+15}" fill="black">{endpoints[0][2]}</text>')
    #pointb
    print(f'<circle cx="{endpoints[0][3] *3}" cy="{endpoints[0][4] *3}" r="4" fill="black"/>')
    print(f'<text x="{(endpoints[0][3] *3)-30}" y="{(endpoints[0][4] *3)+(15)}" fill="black">{endpoints[0][5]}</text>')

    print('</svg>')

if __name__ == "__main__":
    import argparse
    parser = argparse.ArgumentParser()
    parser.add_argument("--id",required=False)

    args = parser.parse_args()
    mid = args.id

    if not mid:
        mid = 0

    if mid == 0:
        allm()
    else:
        specific(mid=mid)
