<!DOCTYPE html>
<!--
Template Name: BRILLIANT Bootstrap Admin Template
Version: 4.5.6
Author: WebThemez
Website: http://www.webthemez.com/
-->
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- Mirrored from webthemez.com/demo/brilliant-free-bootstrap-admin-template/empty.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 17 Feb 2019 07:29:23 GMT -->
<head>
@include('partials._head')
</head>
<body>
<div id="wrapper">
    @include('partials._top')
    <!--/. NAV TOP  -->
    @include('partials._side')
        <!-- /. NAV SIDE  -->
    <div id="page-wrapper">
        <div class="header">
            <h1 class="page-header">
                @yield('page_title')
            </h1>
            <ol class="breadcrumb">
                @yield('breadcrumb')
            </ol>

        </div>
        <div id="page-inner">
            @yield('container')

            <footer><p>All right reserved. Template by: <a href="http://webthemez.com/">WebThemez.com</a></p></footer>
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>
<!-- /. WRAPPER  -->
@include('partials._script')

</body>

<!-- Mirrored from webthemez.com/demo/brilliant-free-bootstrap-admin-template/empty.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 17 Feb 2019 07:29:23 GMT -->
</html>
