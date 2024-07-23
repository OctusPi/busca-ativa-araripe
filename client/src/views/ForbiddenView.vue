<template>
    <div class="vh-100 d-flex flex-column justify-content-center align-items-center">
        <img src="@/assets/imgs/403.svg" class="img-notfound">
        <p class="small text-center txt-color-sec my-2">Parado aí!!! Essa tentativa de burlar as regras será reportada!</p>
        <RouterLink to="/" class="btn btn-sm btn-outline-primary d-flex align-items-center">
            <ion-icon name="flag-outline" class="fs-6 me-1"></ion-icon> Seguir as Regras
        </RouterLink>
    </div>
</template>

<style>
.img-notfound{
    width: 360px;
    height: auto;
}
</style>