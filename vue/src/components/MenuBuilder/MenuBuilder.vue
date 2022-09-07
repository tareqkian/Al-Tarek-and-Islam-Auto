<template>
  <div v-if="items.loading" class="spinner4 my-3">
    <div class="bounce1"></div>
    <div class="bounce2"></div>
    <div class="bounce3"></div>
  </div>
  <ol v-else class="list-group dd-list">
    <li v-for="(item,index) in items.data" :key="index"
        class="list-group-item dd-item" :data-id="item.id">
      <div class="dd-handle h-100 d-inline-block">
<!--        {{ index+1 + ' - ' + item.title}}-->
        {{ index+1 + ' - ' + t(item,'title')}}
        <small v-if="item.route" class="text-muted ms-3"> {{ `${parentMenu}/${item.route}` }}</small>
        <small v-else class="text-muted ms-3"> # </small>
      </div>
      <div class="float-end d-inline-block">
<!--        <i class="feather feather-lock text-success mx-1" v-if="item.route" @click="emit('permissionsGenerator',item)"></i>-->
        <i class="fa fa-edit text-info mx-1" @click="emit('edit_click',item)"></i>
        <i class="fa fa-trash text-danger mx-1" @click="emit('remove_click',item)"></i>
      </div>
<!--             @child_permissionsGenerator="child_permissionsGenerator"-->
      <Child @child_edit_click="child_edit_click"
             @child_remove_click="child_remove_click"
             v-if="item.children.length"
             :items="item.children"
             :parentMenu="parentMenu"
             class="mt-3" />
    </li>
  </ol>
</template>
<script setup>
import Child from "./Child.vue"
import { onMounted } from "vue"
import { useRouter } from "vue-router"

const router = useRouter()
const props = defineProps({
  MenuID: Number,
  items: Object,
  parentMenu: String
})
const emit = defineEmits([
  // 'permissionsGenerator',
  'edit_click',
  'remove_click',
  'order_change'
])
onMounted(
  ()=>{
    $('.dd').nestable({
      expandBtnHTML: '',
      collapseBtnHTML: '',
    });
    $('.dd').on('change', function (e) {
      let builderArray = $(this).nestable('serialize')
      emit('order_change',props.MenuID,builderArray);
    });
  }
)
const child_edit_click = (item)=>{
  emit("edit_click",item)
}
const child_remove_click = (item)=>{
  emit("remove_click",item)
}

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
