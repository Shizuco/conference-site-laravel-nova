<template>
   <div class="container">
        <form action="" id="po">
            <input type="text" id="">
            <input type="text">
            <button @click="click()"></button>
        </form>
    </div>
</template>

<script>
   export default {
        computed: {
            click(){
                console.log(document.getElementById('po').value)
            }
        }
    }
</script>
