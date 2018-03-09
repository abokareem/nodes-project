<template>
    <div class="faq">
        <!-- Section -->
        <div class="faq-container">
            <div class="faq-title-container">
                <h4>{{ $t("faq.title.first") }} <span>{{ $t("faq.title.second") }}</span></h4>
            </div>
            <div class="faq-single-container faq-margin">
                <h5>{{ $t("faq.content.first.title") }}</h5>
                <p>{{ $t("faq.content.first.desc") }}</p>
            </div>
            <div class="faq-single-container">
                <h5>{{ $t("faq.content.second.title") }}</h5>
                <p>{{ $t("faq.content.second.desc") }}</p>
            </div>
            <div class="faq-single-container faq-margin">
                <h5>{{ $t("faq.content.third.title") }}</h5>
                <p>{{ $t("faq.content.third.desc") }}</p>
            </div>
            <div class="faq-single-container">
                <h5>{{ $t("faq.content.fourth.title") }}</h5>
                <p>{{ $t("faq.content.fourth.desc") }}</p>
            </div>
            <div class="faq-single-container faq-margin">
                <h5>{{ $t("faq.content.five.title") }}</h5>
                <p>{{ $t("faq.content.five.desc") }}</p>
            </div>
        </div>
        <!-- End Section -->
    </div>
</template>
<script>
export default{
  name: 'Faq'
}
</script>
<style lang="scss">
@import "../../assets/partials/_const.scss";
.faq {
    display: inline-block;
    width: 100%;
    padding-top: 100px;
    min-height: calc(100vh - 150px);
}
.faq-container {
    display: inline-block;
    width: 80%;
    padding-bottom: 55px;
}
.faq-title-container {
    width: 100%;
    text-align: left;
    h4 {
        font-size: 1.5em;
        margin-bottom: 12px;
        font-weight: 500;
        font-family: "Quicksand", sans-serif;
        line-height: 1.3;
        color: #373e45;
        span {
            color: #f7921a;
        }
    }
}
.faq-single-container {
    display: inline-block;
    width: 41%;
    text-align: left;
    float: left;
    h5 {
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        color: #373e45;
        font-size: 1.125em;
        line-height: 1.25;
        margin-bottom: 8px;
    }
    p {
        font-family: "OpenSans", sans-serif;
        color: #3c3c3c;
        font-size: 16px;
        line-height: 1.75;
        font-weight: 300;
    }
}
.faq-margin {
    margin-right: 9%;
}
@media only screen and (min-width: $minimal) and (max-width: $medium) {
    .faq {
        padding-top: 50px;
    }
    .faq-single-container {
        width: 100%;
    }
}
</style>
