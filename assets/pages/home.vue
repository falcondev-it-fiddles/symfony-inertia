<script setup lang="ts">
import ToDoForm from '../components/ToDo.vue'
import type { ToDo } from '../types'

const props = defineProps<{
  todos: ToDo[]
}>()
</script>

<template>
  <div class="container">
    <ToDoForm v-for="todo of props.todos" :key="todo.id" :todo="todo" />
    <ToDoForm />
  </div>
</template>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
</style>
