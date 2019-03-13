<template>
   <div>
       <li v-for="todo in todos">
           <slot :id="todo.id" :text="todo.text">
               {{ todo.text }}
           </slot>
       </li>

   </div>
</template>

<script>
    export default {
        name: "scoped-slot-component",
        props:['todos']
    }
</script>

<style scoped>

</style>