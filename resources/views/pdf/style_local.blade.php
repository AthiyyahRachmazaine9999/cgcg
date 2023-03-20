<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
    @page {
        margin-top: 0px;
        margin-left: 20px;
        margin-right: 20px;
        margin-bottom: 0px;
    }

    * {
        margin: 0;
        padding: 0;
    }

    body {
        margin-left: 20px;
        margin-right: 20px;
        font-size: 12px;
        line-height: 15%;
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    }

    .all {
        margin-left: 20px;
        margin-right: 20px;
        font-size: 12px;
        line-height: 15%;
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        background-color: white;
    }

    #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #customers td,
    #customers th {
        border: 1px solid #fcfcfc;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        background-color: #2da9e3;
        color: white;
    }

    #customers tfoot {
        text-align: right;
        border: none;
    }

    #look {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }


    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    h7,
    .h1,
    .h2,
    .h3,
    .h4,
    .h5,
    .h6,
    .h7 {
        margin-bottom: 0.500rem;
        font-weight: 400;
        line-height: 1;
    }

    h1,
    .h1 {
        font-size: 1.5625rem;
    }

    h2,
    .h2 {
        font-size: 1.4375rem;
    }

    h3,
    .h3 {
        font-size: 1.3125rem;
    }

    h4,
    .h4 {
        font-size: 1.1875rem;
    }

    h5,
    .h5 {
        font-size: 1.0625rem;
    }

    h6,
    .h6 {
        font-size: 0.9375rem;
    }

    h7,
    .h7 {
        font-size: 0.6375rem;
    }

    .judul {
        border: 1px solid black;
        line-height: 0.5px;
        position: static;
        width: 100%;
        height: 500px;
        margin-top: 2px;
        text-align: center;
        padding-top: 15px;
    }

    .judul_kedua {
        border: 1px solid black;
        line-height: 0.5px;
        column-count: 3;
        width: 100%;
        height: 50%;
        margin-top: 2px;
        text-align: center;
        padding-top: 15px;
    }

    .column {
        float: left;
        padding: 10px;
        height: 300px;
        /* Should be removed. Only for demonstration */
    }

    #tr_kotak {
        border: 2px solid black;
        width: 10%;
        height: 30%;
    }

    #th_kanan {
        text-align: right;
        padding-left: 50px;
    }

    #payment {
        padding-top: 20%;
        width: 70%;
        font-family: Arial, Helvetica, sans-serif;
        padding: 8px;
    }

    #payment th {
        width: 20%;
        padding-left: 15px;
        padding-top: 20px;
        padding-bottom: 12px;
        text-align: left;
    }

    #payment2 {
        /* border: 1px solid black; */
        width: 100%;
        font-family: Arial, Helvetica, sans-serif;
    }

    #paraf_tbl {
        /* border: 1px solid black; */
        width: 100%;
        text-align: center;
        font-family: Arial, Helvetica, sans-serif;
        padding-top: 10px;
    }

    #payment2 th {
        width: 20%;
        padding-left: 15px;
        padding-top: 15px;
        line-height: 2px;
        padding-bottom: 12px;
        text-align: left;
    }

    #nominal {
        border: 1px solid black;
        padding-top: 15px;
        width: 50%;
        height: 15px;
        margin: 20px;
        margin-top: 5px;
    }

    #terbilang {
        border: 1px solid black;
        padding-top: 15px;
        width: 70%;
        height: 15px;
        margin: 20px;
        margin-top: 5px;

    }


    #nominal2 {
        border: 1px solid black;
        padding-top: 15px;
        width: 70%;
        height: 120px;
        line-height: 1.6;
        position: static;
        margin: 20px;
        margin-top: 5px;
        text-align: center;

    }

    #nominal_kedua {
        border: 1px solid black;
        padding-top: 15px;
        width: 50%;
        height: 15px;
        margin: 20px;
        margin-top: 5px;
    }

    #terbilang_kedua {
        border: 1px solid black;
        padding-top: 15px;
        width: 70%;
        height: 15px;
        margin: 20px;
        margin-top: 5px;
    }


    #nominal_kedua2 {
        border: 1px solid black;
        padding-top: 15px;
        width: 70%;
        height: 120px;
        line-height: 1.6;
        position: static;
        margin: 20px;
        margin-top: 5px;
        text-align: center;

    }


    #paraf {
        border: 1px solid black;
        margin-top: 3px;
        margin-left: 75%;
        width: 20%;
        height: 380px;
    }

    #paraf_kedua {
        border: 1px solid black;
        margin-top: 3px;
        margin-left: 75%;
        width: 20%;
        height: 380px;
    }


    #nominal tr {
        border: 1px solid black;
        padding-left: 20px;
    }

    .column {
        float: left;
        padding: 10px;
        height: 300px;
    }

    .group-right {
        width: 40%;
    }

    .group-left {
        width: 90%;
    }
    </style>
</head>