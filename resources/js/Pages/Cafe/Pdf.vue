<template>
    <div class="page">
        <img :src="cafe.logo" class="logo" alt="">
        <h4 class="cafe-name">{{ cafe.name }}</h4>

        <qrcode-vue :value="`${cafe_index_route}/generate/${cafe.id}`" :size="300" level="H"></qrcode-vue>
        <label style="padding: 10px; font-size: 14px;">Scan to sign-in or sign-out from cafe</label>

        <div class="servicer-div">
            <label class="servicer-text">A service provided by</label>
            <img src="/images/cafe_booking_logo.png" class="service-logo" alt="">
        </div>
    </div>
</template>

<style>
    @import url('https://fonts.googleapis.com/css2?family=East+Sea+Dokdo&display=swap');

    .page-break {
        page-break-after: always;
    }
    .logo {
        width: 10rem;
        height: 10rem;
        object-fit: contain;
        border-radius: 50%;
        border-width: 2px;
        border-color: rgb(219, 39, 119);
    }
    .page {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding-top: 12rem;
    }
    .cafe-name {
        font-family: 'East Sea Dokdo', cursive;
        font-size: 50px;
    }
    .servicer-div {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: flex-end;
        text-align: center;
        padding-top: 20px;
    }
    .servicer-text {
        font-family: 'East Sea Dokdo', cursive;
        color: rgb(219, 39, 119);
        font-size: 40px;
        padding-right: 10px;
    }
    .service-logo {
        height: 6rem;
        object-fit: contain;
    }
</style>

<script>
import QrcodeVue from 'qrcode.vue'

export default {
    props: ['cafe', 'cafe_index_route'],
    data() {
        return {
            
        }
    },
    components: {
        QrcodeVue
    }
}
</script>