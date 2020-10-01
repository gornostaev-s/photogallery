<!DOCTYPE html>
<html lang="ru" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Фотогалерея</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style media="screen">
            header{
                background: #000;
                color: #fff;
                line-height: 50px;
            }
            header a.addphoto {
                color: #fff;
                float: right;
                line-height: 30px;
                margin: 10px 0;
                border: 1px solid #fff;
                padding: 0 5px;
                border-radius: 4px;
            }
            .get-photo{
                color: #000;
                line-height: 30px;
                margin: 10px 0;
                border: 1px solid #000;
                padding: 5px 10px;
                border-radius: 4px;
                margin-top: 30px;
                display: inline-block;
            }
            #photogallery{
                margin: 30px auto;
            }
            #photogallery img {
                margin: 5px 0;
            }
            .modal .overflow {
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0;
                left: 0;
                z-index: 4;
                background: rgba(0,0,0,.5);
            }
            .modal .modal-content {
                height: auto;
                z-index: 5;
                background: #fff;
                width: min-content;
                padding: 0;
                top: 0;
                margin: 15px auto;
                min-width: 500px;
                min-height: 350px;
            }
            .modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                z-index: 4;
                display: block;
            }
            .close{
                position: absolute;
                top: 10px;
                right: 10px;
                color: #000;
            }
            .addform {
                padding: 0 15px;
                padding-bottom: 15px;
            }
            .addform input[type=date], .addform input[type=tel], .addform input[type=email]{
                width: 100%;
            }
            label{
                margin-top: 10px;
            }
            .addform input[type=submit]{
                margin-top: 20px;
            }
            .addform input[type="file"]{
                margin-top: 20px;
            }

            img{
                max-width: 100%;
            }

            .row > .img-container {
                min-height: 180px;
            }

            .error{
                font-size: 12px;
                display: block;
                color: #ff0000;
            }

            h2{
                width: 100%;
                margin: 20px 0;
            }

            .photoblock{
                margin: 20px 0;
            }

        </style>
    </head>
    <body>


        <header>
            <div class="container">
                <div class="row">
                    <div class="col-6 logo">
                        Фотогалерея
                    </div>
                    <div class="col-6">
                        <a class="addphoto" href="#">Загрузить фото</a>
                    </div>
                </div>
            </div>
        </header>

        <section id="photogallery">
            <div class="container gal">

            </div>


            <div class="text-center">
                <a class="get-photo" href="#">Загрузить еще</a>
            </div>

        </section>

        <div style="display: none;" class="modal">
            <div class="overflow">

            </div>
            <div class="modal-content">
                <div class="close">
                    X
                </div>

                <h2 class="text-center">Загрузить фото</h2>
                <form class="addform" action="photo.php" method="post">
                    <label for="tel">Номер телефона</label>
                    <input id="tel" placeholder="+7 (999) 999-9999" type="tel" />
                    <p class="error tel-error"></p>
                    <label for="date">Дата рождения</label>
                    <input id="date" type="date" />
                    <p class="error date-error"></p>
                    <label for="email">Почта</label>
                    <input placeholder="mail@example.com" id="email" type="email" />
                    <p class="error email-error"></p>
                    <center>
                        <input type="file" multiple="multiple" accept=".txt,image/*">
                        <p class="error photo-error"></p>
                    </center>

                    <center><input type="submit" value="Отправить"></center>
                </form>
            </div>
        </div>

        <script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
    <script src="/js/main.js"></script>
    </body>
</html>
