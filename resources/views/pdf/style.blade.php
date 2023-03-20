<head>
    <meta charset="utf-8">

    <style>

        @page {
            margin-top: 0px;
            margin-left: 20px;
            margin-right: 20px;
            margin-bottom: 20px;
        }

        body {
            margin-top: 4.7cm;
            margin-left: 20px;
            margin-right: 20px;
            margin-bottom: 2cm;
            font-size: 11px;
            line-height: 20px;
            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            color: #555;
        }

        header {
            position: fixed;
            top: -15px;
        }

        footer {
            position: fixed;
        }



        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #fcfcfc;
            padding: 5px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 5px;
            padding-bottom: 5px;
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

        #look td,
        #look th {
            border: 1px solid #2da9e3;
            padding: 5px;
        }

        #look tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #look tr:hover {
            background-color: #ddd;
        }

        #look th {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        #look tfoot {
            text-align: right;
            border: none;
        }
 
        /* partial */

        #notes {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #notes td,
        #notes th {
            border: 1px solid #2da9e3;
            padding: 5px;
        }

        #notes tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #notes tr:hover {
            background-color: #ddd;
        }

        #notes th {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        #notes tfoot {
            text-align: right;
            border: none;
        }

        /* slip gaji */
        #slipheader {
            border: 1px solid #363636;
            border-radius: 10px;
            font-family: Arial, Helvetica, sans-serif;
            width: 100%;
        }

        #slipheader td,
        #slipheader th {
            padding: 5px;
        }

        #slipheader tr:hover {
            background-color: #ddd;
        }

        #slipheader th {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        #slipheader tfoot {
            text-align: right;
            border: none;
        }

        #slip {
            border: 1px solid #363636;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #slip td,
        #slip th {
            padding: 5px;
        }

        #slip tr:hover {
            background-color: #ddd;
        }

        #slip th {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        #slip tfoot {
            text-align: right;
            border: none;
        }


        #slip {
            border: 1px solid #363636;
            border-radius: 10px;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #slip td,
        #slip th {
            padding: 5px;
        }

        #slip tr:hover {
            background-color: #ddd;
        }

        #slip th {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        #slip tfoot {
            text-align: right;
            border: none;
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

        .nomer {
            font-weight: 400;
            font-size: 1.1875rem;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .mt-3 {
            margin-top: 1.25rem !important;
        }

        hr.solid {
            border: 1px solid #e8e3e3;
        }

        .copyright {
            width: 100%;
            position: absolute;
            bottom: 70px;
            padding-top: 7px;
            padding-bottom: 7px;
            color: #0088e3;
            border-top: 1px solid #0088e3;
            border-bottom: 1px solid #0088e3;
        }

        .title {
            width: 100%;
            bottom: 70px;
            padding-top: 0px;
            padding-bottom: 7px;
        }

        #set_customers {
            font-family: Arial;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
            margin-right: 20px;
            padding-right: 20px;
        }

        #set_customers td,
        #set_customers th {
            border: 1px solid block;
            padding: 8px;
            margin-right: 20px;
            padding-right: 20px;
        }


        #set_customers tr:nth-child(even) {
            background-color: white;
        }

        #set_customers tr:hover {
            background-color: #ddd;
        }

        #set_customers th,
        .colour {
            padding-top: 12px;
            background-color: #ADD8E6;
            color: black;
            margin-right: 2px;
            padding-right: 2px;
        }

        #set_customerss th {
            padding-top: 12px;
            padding-bottom: 12px;
            color: white;
        }

        #set_customers tfoot {
            text-align: right;
            border: none;
        }

        /* table */
        #tables_sett {
            width: 700px;
            height: 710px;
            border: 1px solid black;
            margin-right: 2px;
        }

        #tables_sett td,
        #tables_sett th {
            border: 1px solid #fcfcfc;
            padding: 8px;
        }

        .p_text {
            font-size: 10px;
            color: black;
            text-align: center;
        }
        
    </style>
</head>