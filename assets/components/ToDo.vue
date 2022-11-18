<script lang="ts" setup>
import { ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import type { ToDo } from '../types'

const props = defineProps<{
  todo?: ToDo
}>()

const name = ref(props.todo?.name ?? '')
const done = ref(props.todo?.done ?? false)

const submitting = ref(false)

const submitToDoUpdate = () => {
  submitting.value = true

  const requestOptions = {
    onFinish: () => {
      submitting.value = false
      if (!props.todo) {
        name.value = ''
        done.value = false
      }
    },
  }
  if (props.todo) {
    Inertia.patch(`/todos/${props.todo.id}`, {
      name: name.value,
      done: done.value,
    }, requestOptions)
  }
  else {
    Inertia.post('/todos', {
      name: name.value,
      done: done.value,
    }, requestOptions)
  }
}

const deleteToDo = () => {
  if (!props.todo)
    return

  submitting.value = true

  Inertia.delete(`/todos/${props.todo.id}`, {
    onFinish: () => {
      submitting.value = false
    },
  })
}
</script>

<template>
  <form class="todo-container" @submit.prevent="submitToDoUpdate">
    <input v-model="done" type="checkbox">
    <input v-model="name" type="text" placeholder="Name...">
    <button type="submit" :disabled="submitting">
      {{ todo ? 'Save' : 'Create' }}
    </button>
    <button v-if="todo" type="button" :disabled="submitting" @click="deleteToDo">
      X
    </button>
  </form>
</template>

<style scoped>
.todo-container {
  display: flex;
  align-items: center;
  gap: 0.25rem;
}
</style>
