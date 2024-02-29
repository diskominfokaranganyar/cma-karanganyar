<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Diskominfo News</title>
    <link rel="icon" type="image/x-icon" href="asset/Logo.png">

    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
    />
    <link rel="stylesheet" href="index.css">

    <style>
        .header-offline{
            border-left-style: solid;
            border-color: #070F22;
            border-width: 10px;
            font-weight: bold;
        }

        .header-online{
            border-left-style: solid;
            border-color: #070F22;
            border-width: 10px;
            font-weight: bold;
        }

        .header-news{
            border-left-style: solid;
            border-color: #070F22;
            border-width: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        .card {
            border-radius: 20px;
        }

        .navbar {
            background-color: #070F22 ;
        }

        .navbar-brand img {
            width: 250px;
            height: 50px;
        }

        #navbar {
            background-color: #070F22;
        }

        .nav-link {
            color: white !important;
            margin-right: 20px;
        }

        #button-kategori {
            background-color: #070F22;
            color: white;
        }

        #btn-keyword {
            background-color: #070F22;
            color: white;
        }

        #score-analis {
            background-color: green;
        }
    </style>

</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light w-100 fixed-top">
        <div class="container-fluid" id="navbar">
            <a class="navbar-brand ms-3" href="#"><img src="asset/logo-white.png" alt=""></a>
            <button class="navbar-toggler text-end" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav pt-1 me-3 ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-bold" aria-current="page" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" aria-current="page" href="#headeroffline">Offline</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" aria-current="page" href="#header-online">Online</a>
                    </li>
                </ul>
                <a href="admin/">
                    <button class="btn text-white nav-login fw-bold me-4">LOGIN</button>
                </a>
            </div>
        </div>
    </nav>