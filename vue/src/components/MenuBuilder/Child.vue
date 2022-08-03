<template>
  <ol class="list-group dd-list">
    <li v-for="(item,index) in items" :key="index"
        class="list-group-item dd-item"
        :data-id="item.id">
      <div class="dd-handle h-100 d-inline-block">
        {{ index+1 + ' - ' + item.title}}
        <small v-if="item.route" class="text-muted ms-3"> {{ `${parentMenu}/${item.route}` }}</small>
        <small v-else class="text-muted ms-3"> # </small>
      </div>
      <div class="float-end d-inline-block">
<!--        <i class="feather feather-lock text-success mx-1" v-if="item.route" @click="emit('child_permissionsGenerator',item)"></i>-->
        <i class="fa fa-edit text-info mx-1" @click="emit('child_edit_click',item)"></i>
        <i class="fa fa-trash text-danger mx-1" @click="emit('child_remove_click',item)"></i>
      </div>
      <Child v-if="item.children.length"
             :items="item.children"
             :parentMenu="parentMenu" />
    </li>
  </ol>
</template>

<script setup>
const props = defineProps({
  items: Array,
  parentMenu: String
})
const emit = defineEmits([
  // 'child_permissionsGenerator',
  'child_edit_click',
  'child_remove_click',
  'child_order_change'
])
</script>
<style scoped>
.fa, .feather{
  font-size: 20px;
  cursor: pointer;
}
.dd-handle {
  width: 90% !important;
}
</style>
