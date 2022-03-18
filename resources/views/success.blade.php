<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Twitter Account Details</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.11.0/mdb.min.css" rel="stylesheet" /> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        .gradient-custom {
            /* fallback for old browsers */
            background: #f6d365;

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to right bottom, rgba(246, 211, 101, 1), rgba(253, 160, 133, 1));

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(to right bottom, rgba(246, 211, 101, 1), rgba(253, 160, 133, 1))
        }
    </style>
</head>

<body>
    <section class="vh-100" style="background-color: #f4f5f7;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <h1 class="text-center text-success">Success!</h1>
                <div class="col col-lg-6 mb-4 mb-lg-0">
                    <div class="card mb-3" style="border-radius: .5rem;">
                        <div class="row g-0">
                            <div class="col-md-4 gradient-custom text-center text-white" style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                <img src="{{$data['image']}}" alt="Avatar" class="img-fluid my-5" style="width: 80px;" />
                                <h5>{{$data['name']}}</h5>
                                <p>{{$data['user_name']}}</p>
                                <i class="far fa-twitter mb-5"><a href="https://twitter.com/{{$user['user_name']}}"></a></i>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body p-4">
                                    <h6>Information</h6>
                                    <hr class="mt-0 mb-4">
                                    <div class="row pt-1">
                                        <div class="col-12 mb-12">
                                            <h6>Email</h6>
                                            <p class="text-muted">{{$data['email']}}</p>
                                        </div>
                                        <!-- <div class="col-6 mb-3">
                                            <h6>Phone</h6>
                                            <p class="text-muted">123 456 789</p>
                                        </div> -->
                                    </div>
                                    <!-- <h6>Projects</h6>
                                    <hr class="mt-0 mb-4">
                                    <div class="row pt-1">
                                        <div class="col-6 mb-3">
                                            <h6>Recent</h6>
                                            <p class="text-muted">Lorem ipsum</p>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <h6>Most Viewed</h6>
                                            <p class="text-muted">Dolor sit amet</p>
                                        </div>
                                    </div> -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>