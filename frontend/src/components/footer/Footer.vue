<template>
    <div class="footer">
        <div class="footer-copy-container">
            <p>Copyright &copy; 2018 NodePub</p>
        </div>
        <div class="footer-social-container">
            <ul class="social">
                <li><a href="#"><em class="fa fa-facebook"></em></a></li>
                <li><a href="#"><em class="fa fa-twitter"></em></a></li>
                <li><a href="#"><em class="fa fa-linkedin"></em></a></li>
                <li><a href="#"><em class="fa fa-google-plus"></em></a></li>
            </ul>
        </div>
    </div>
</template>
<script>
export default{
  name: 'foo-ter'
}
</script>
<style lang="scss">
.footer {
    background: #0d1120;
    text-align: center;
    width: 100%;
}
.footer-copy-container {
    display: inline-block;
    width: 49%;
    min-width: 300px;
    text-align: left;
    p {
        color: #fff;
        font-family: "OpenSans", sans-serif;
        font-weight: 300;
        margin-left: 20px;
    }
}
.footer-social-container {
    display: inline-block;
    width: 49%;
    min-width: 300px;
    text-align: right;
    ul {
        margin-right: 90px;
        list-style: none;
        li {
            display: inline-block;
            margin-left: 20px;
            a > em {
                color: #ffffff;
                &:hover {
                    color: #f7921a;
                }
                transition: all .6s;
            }
        }
    }
}
.fa {
    display: inline-block;
    font: 16px FontAwesome;
    color: #fff;
}
.fa-facebook:before {
    content: "\f09a";
}
.fa-google-plus:before {
    content: "\f0d5";
}
.fa-twitter:before {
    content: "\f099";
}
.fa-linkedin:before {
    content: "\f0e1";
}
</style>
