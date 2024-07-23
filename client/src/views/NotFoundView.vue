<template>
    <div class="vh-100 d-flex flex-column justify-content-center align-items-center">
        <img src="@/assets/imgs/404.svg" class="img-notfound">
        <p class="small text-center txt-color-sec my-2">Acho que você se perdeu, click no link pra voltar ao inicio!</p>
        <RouterLink to="/" class="btn btn-sm btn-outline-primary d-flex align-items-center">
            <ion-icon name="extension-puzzle-outline" class="fs-6 me-1"></ion-icon> Voltar a Civilização
        </RouterLink>
    </div>
</template>

<style>
.img-notfound{
    width: 360px;
    height: auto;
}
</style>