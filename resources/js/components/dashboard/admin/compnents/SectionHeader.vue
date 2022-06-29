<template>
    <div>
        <h2>{{text}}</h2>
        <hr>
    </div>
</template>
<script>
export default {
    props: ['text']
}
</script>
<style scoped>
    h2{
        margin-top: 15px;
        color: #2F434A;
    }
    hr {
        position: relative;
        opacity: 1;
        color: rgba(47, 67, 74, 0.12);
    }
    hr::before{
        content: '';
        display: block;
        position: absolute;
        height: 5px;
        width: 5%;
        background-color: #238F63;
        opacity: 1;
        top: -2px;
        border-radius: 10px;

    }
</style>
