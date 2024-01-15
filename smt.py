import sys
import qrcode
import os

asset_path = os.getcwd()+"\\assets\\imgs\\"

print('You enter :', str(sys.argv[1]))

msizebox = 1
if len(sys.argv) > 2:
    msizebox = sys.argv[2]
qr = qrcode.QRCode(
    version=1,
    error_correction=qrcode.constants.ERROR_CORRECT_L,
    box_size=msizebox,
    border=1,
)
qr.add_data(sys.argv[1])
qr.make(fit=True)

img = qr.make_image(fill_color="black", back_color="white")
imgname = sys.argv[1]
imgname = imgname.replace("/", "xxx")
imgname = imgname.replace(" ", "___")
imgname = imgname.replace("|", "lll")
imgname = imgname.replace("\t", "ttt")

# img.save(config['GENERATED_ASSET_QRCODE_PATH']+":\\Apache24\\htdocs\\wms\\assets\\imgs\\"+imgname+".png")
img.save(asset_path+imgname+".png")
