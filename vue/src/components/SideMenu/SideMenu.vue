<template>
  <ul class="side-menu">
    <li v-if="menuItems.loading" class="slide">
      <div class="spinner4 my-3">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
      </div>
    </li>
    <li v-else class="slide" v-for="item in menuItems.data.filter(x=>$can(`browse_${x.permissionsLayout}`) || x.permissionsLayout === '_')" :key="item.id">
      <a v-if="item.children.filter(x=>$can(`browse_${x.permissionsLayout}`)).length || (!item.importedComponent && item.children.filter(x=>$can(`browse_${x.permissionsLayout}`)).length)" class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
        <i class="sidemenu_icon" :class="[item.icon_class]"></i>
        <span class="side-menu__label">{{ t(item,'title') }}</span>
        <i v-if="item.children.length" class="angle fa fa-angle-right"></i>
      </a>
      <router-link v-else-if="item.importedComponent" active-class="active"
                   :to="{name : item.title}"
                   class="side-menu__item">
        <i class="sidemenu_icon" :class="[item.icon_class]"></i>
        <span class="side-menu__label"> {{ t(item,'title') }} </span>
        <br>
      </router-link>
      <Child v-if="item.children.filter(x=>$can(`browse_${x.permissionsLayout}`)).length" :children="item.children" />
    </li>
  </ul>
</template>

<script setup>
import Child from "./Child.vue";
const props = defineProps({
  menuItems: Object,
})

const fileName = (val) => {
  const parentComp = val.split('/')[val.split('/').length - 2];
  val = val.split("/").pop().replace(/.vue/g,'')
  val = val.substr(0, 1).toLowerCase() + val.substr(1)
  val = `${parentComp}.${val}`;
  return val;
};
</script>
