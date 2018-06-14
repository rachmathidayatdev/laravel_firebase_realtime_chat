<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="col-sm-12">
                    <h1>Coba</h1>
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <button type="button" class="btn btn-info" onClick="submitData()">Submit</button>
                    </form>
                </div>
                <div class="col-sm-12">
                    <div class="container" style="margin-bottom: 100px">
                        <h1>Hasil Post Data Firebase</h1>
                        <table class="table table-bordered">
                            <thead>
                                <th>Name</th>
                                <th>Action</th>
                            </thead>
                            <tbody id="result">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
    <script>
        // Initialize Firebase
        var config = {
            apiKey: "api key dari firebase",
            authDomain: "auth domain dari firebase",
            databaseURL: "database url dari firebase",
            projectId: "project id dari firebase",
            storageBucket: "",
            messagingSenderId: "messaging sender id dari firebase"
        };
        firebase.initializeApp(config);

        var database = firebase.database();
        var postData = database.ref().child("post_data");

        //listen data dari firebase
        postData.on('value', function(snapshot) {
            if(snapshot.val() !== null){
                var result = $("#result");
                result.html("");
                snapshot.forEach(function(item){
//                    console.log(JSON.stringify(item.val()))
                    result.append(
                        `<tr>
                            <td>`+item.val().name+`</td>
                            <td><button class="btn btn-success" title_id="`+item.val().id+`" title_key="`+item.key+`" onClick\=\deleteData(event)\>Delete</button></td>
                        </tr>`
                    );
                })
            }else{
                var result = $("#result");
                result.html("");
            }
        });
        //sampai sini

        //child_added firebase
        postData.on("child_added", function(snapshot){
//            console.log("[child_added] key:"+snapshot.key)
//            console.log("[child_added] val:"+JSON.stringify(snapshot.val()))
        })
        //sampai sini

        //child_changed firebase
        postData.on("child_changed", function(snapshot){
//            console.log("[child_changed] key:"+snapshot.key)
//            console.log("[child_changed] val:"+JSON.stringify(snapshot.val()))
        })
        //sampai sini

        //child_removed firebase
        postData.on("child_removed", function(snapshot){
//            console.log("[child_removed] key:"+snapshot.key)
//            console.log("[child_removed] val:"+JSON.stringify(snapshot.val()))
        })
        //sampai sini

        function submitData(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ url('/store_post_data') }}",
                data: {name: $('#name').val()},
                success: function(response) {
                    var id = response.id;
                    var name = response.name;

                    //store data ke firebase
                    postData.push({
                        id: id,
                        name: name
                    });
                    //sampai sini

                    $('#name').val("");
                }
            });
        }

        function deleteData(e){
            var id = e.target.attributes.title_id.value;
            var key = e.target.attributes.title_key.value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "DELETE",
                url: "{{ url('/delete_post_data') }}",
                data: {id: id, key: key},
                success: function (response) {
                    if(response.status === 'berhasil'){
                        //hapus data di firebase
                        database.ref().child("post_data/"+key).remove();
                        //sampai sini
                    }
                }
            });
        }
    </script>
    </body>
</html>
