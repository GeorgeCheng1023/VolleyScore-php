<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>

<style>
    /*--------------------------------------------------------------
# Hero Section
--------------------------------------------------------------*/
    #hero {
        width: 100%;
        height: 100vh;
        position: relative;
        background: url("../img/hero-bg.jpg") top center;
        background-size: cover;
        position: relative;
    }

    #hero:before {
        content: "";
        background: rgba(255, 255, 255, 0.8);
        position: absolute;
        bottom: 0;
        top: 0;
        left: 0;
        right: 0;
    }

    #hero .container {
        padding-top: 80px;
    }

    #hero h1 {
        margin: 0;
        font-size: 56px;
        font-weight: 700;
        line-height: 72px;
        color: #124265;
        font-family: "Poppins", sans-serif;
    }

    #hero h2 {
        color: #5e5e5e;
        margin: 10px 0 0 0;
        font-size: 22px;
    }

    #hero .btn-get-started {
        font-family: "Poppins", sans-serif;
        font-weight: 500;
        font-size: 14px;
        letter-spacing: 0.5px;
        display: inline-block;
        padding: 14px 50px;
        border-radius: 5px;
        transition: 0.5s;
        margin-top: 30px;
        color: #fff;
        background: #2487ce;
    }

    #hero .btn-get-started:hover {
        background: #3194db;
    }

    #hero .icon-boxes {
        margin-top: 100px;
    }

    #hero .icon-box {
        padding: 50px 30px;
        position: relative;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 0 29px 0 rgba(18, 66, 101, 0.08);
        transition: all 0.3s ease-in-out;
        border-radius: 8px;
        z-index: 1;
    }

    #hero .icon-box .title {
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 18px;
    }

    #hero .icon-box .title a {
        color: #124265;
        transition: 0.3s;
    }

    #hero .icon-box .description {
        font-size: 15px;
        line-height: 28px;
        margin-bottom: 0;
    }

    #hero .icon-box .icon {
        margin-bottom: 20px;
        padding-top: 10px;
        display: inline-block;
        transition: all 0.3s ease-in-out;
        font-size: 36px;
        line-height: 1;
        color: #2487ce;
    }

    #hero .icon-box:hover {
        transform: scale(1.08);
    }

    #hero .icon-box:hover .title a {
        color: #2487ce;
    }

    @media (min-width: 1024px) {
        #hero {
            background-attachment: fixed;
        }
    }

    @media (max-height: 800px) {
        #hero {
            height: auto;
        }
    }

    @media (max-width: 992px) {
        #hero {
            height: auto;
        }

        #hero h1 {
            font-size: 28px;
            line-height: 36px;
        }

        #hero h2 {
            font-size: 18px;
            line-height: 24px;
        }
    }
</style>

</head>



<body id="wrapper">


    <?php include "components\header.php"; ?>

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-9 text-center">
                    <h1>歡迎來到排球計分系統</h1>
                    <h2>管理、編輯、分析你的球隊</h2>
                </div>
            </div>
            <div class="text-center">
                <a href="login.php" class="btn-get-started scrollto">Get Started</a>
            </div>

            <!-- <div class="row icon-boxes">
                <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="200">
                    <div class="icon-box">
                        <div class="icon"><i class="ri-stack-line"></i></div>
                        <h4 class="title"><a href="">Lorem Ipsum</a></h4>
                        <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="300">
                    <div class="icon-box">
                        <div class="icon"><i class="ri-palette-line"></i></div>
                        <h4 class="title"><a href="">Sed ut perspiciatis</a></h4>
                        <p class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="400">
                    <div class="icon-box">
                        <div class="icon"><i class="ri-command-line"></i></div>
                        <h4 class="title"><a href="">Magni Dolores</a></h4>
                        <p class="description">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="500">
                    <div class="icon-box">
                        <div class="icon"><i class="ri-fingerprint-line"></i></div>
                        <h4 class="title"><a href="">Nemo Enim</a></h4>
                        <p class="description">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis</p>
                    </div>
                </div>

            </div> -->
        </div>
    </section><!-- End Hero -->

    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
