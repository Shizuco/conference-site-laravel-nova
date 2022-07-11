<template>
   <div>
    <auth style="height: 80px"></auth>
     <div class="row justify-content-center">
            <div class="col-md-8">
                <input type="text" name="title"  id="title" class="form-control" placeholder="Hазвание">
            <div class="form-row">
                <div class="col">
                    <input type="date"  id="date" class="form-control" name="date" max="2032-12-31">
                </div>
                <div class="col">
                    <input type="time" name="time" id="time" class="form-control">
                </div>
                <input type="text" id="lat" name="address_lat" class="form-control">
                <input type="text" id="lon" name="address_lon" class="form-control">
                <input type="text" id="country" name="country" class="form-control">
            </div>
            <br>
            <div id="map" style="width:100%;height:400px;"></div>
            </div>
            <div class="col-md-8">
                <router-link :to="{name: 'MainPage'}">Назад</router-link>
                <button @click="createConference()">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        computed: {
            createConference(){
                let href = document.location.origin
                let data = {
                    'title' : document.getElementById('title').value,
                    'date' : document.getElementById('date').value,
                    'time' : document.getElementById('time').value,
                    'address_lat' : document.getElementById('lat').value,
                    'address_lon' : document.getElementById('lon').value,
                    'country' : document.getElementById('country').value
                }
                console.log(data)
                this.$store.dispatch('ajaxConferenceCreate', data)
                this.$store.getters.createConference
                document.location.href = href
            }
        }
    }
</script>
