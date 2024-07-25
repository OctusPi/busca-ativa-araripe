<script setup>
    const props = defineProps({
        icon:{type: String},
        title:{type: String, required:true},
        desc: {type: String}
    })
</script>

<template>
    <div class="title-box d-flex align-items-center mb-3">
        <ion-icon :name="props.icon" class="title-box-icon me-2 fs-5"></ion-icon>
        <div>
            <h2 class="p-0 m-0">{{ props.title }}</h2>
            <p class="p-0 m-0">{{ props.desc }}</p>
        </div>
    </div>
</template>