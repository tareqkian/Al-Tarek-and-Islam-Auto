<template>
  <ul class="slide-menu">
    <li v-for="child in children.filter(x=>$can(`browse_${x.permissionsLayout}`))" :key="child.id">
      <a v-if="child.children.filter(x=>$can(`browse_${x.permissionsLayout}`)).length || (!child.importedComponent && child.children.filter(x=>$can(`browse_${x.permissionsLayout}`)).length)" class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
        <i :class="child.icon_class"></i>
        <span class="side-menu__label">{{ t(child,'title') }}</span>
        <i v-if="child.children.length" class="angle fa fa-angle-right"></i>
      </a>
      <router-link v-else-if="child.importedComponent" active-class="active"
                   :to="{name : child.title}"
                   class="slide-item">
        <span class="side-menu__label"> {{ t(child,'title') }} </span>
      </router-link>
      <Child v-if="child.children.filter(x=>$can(`browse_${x.permissionsLayout}`)).length" :children="child.children" />
    </li>
  </ul>
</template>

<script setup>
const props = defineProps({
  children: Object,
})
const fileName = (val) => {
  const parentComp = val.split('/')[val.split('/').length - 2];
  val = val.split("/").pop().replace(/.vue/g,'')
  val = val.substr(0, 1).toLowerCase() + val.substr(1)
  val = `${parentComp}.${val}`;
  return val;
};
</script>
