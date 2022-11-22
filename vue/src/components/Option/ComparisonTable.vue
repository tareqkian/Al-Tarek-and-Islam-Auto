<template class="overflow-hidden">
  <div v-if="type === 'diff' && autobansOptions.data.length === 1" class="h4 text-center"> Options </div>
  <div v-if="type === 'diff' && autobansOptions.data.length === 2" class="h4 text-center"> Diff </div>
  <table class="table table-hover table-striped table-bordered table-center">
    <tbody v-if="autobansOptions.data.length" v-show="type !== 'diff'">
      <tr>
        <td class="py-1" width="30%"> Brand </td>
        <td class="py-1 position-relative"
            :width="`${70/autobansOptions.data.length}%`"
            v-for="(autoban,index) in autobansOptions.data"
            v-show="(!index && type === 'diff' && autobansOptions.data.length === 2) || (!index && type === 'diff' && autobansOptions.data.length === 1) || type !== 'diff'">
          <span @click="emits('removeFromComparison',autoban)"
                v-if="autobansOptions.data.length > 2"
                class="fa fa-trash-o text-danger position-absolute top-0 end-0 p-1" style="z-index: 999;"></span>
          <div class="row p-0">
            <div class="col-12 d-flex justify-content-center align-items-center">
              <Image width="100" :src="autoban.model.brand.brand_image" />
            </div>
            <div class="col pb-0 pt-3">{{ t(autoban.model.brand,'brand_title') }}</div>
          </div>
        </td>
      </tr>
      <tr>
        <td class="py-1" width="30%"> Model </td>
        <td class="py-1" :width="`${70/autobansOptions.data.length}%`" v-for="(autoban,index) in autobansOptions.data" v-show="(!index && type === 'diff' && autobansOptions.data.length === 2) || (!index && type === 'diff' && autobansOptions.data.length === 1) || type !== 'diff'">
          <div class="row p-0">
            <div class="col-12 d-flex justify-content-center align-items-center">
              <Image width="150" :src="autoban.model.model_image" />
            </div>
            <div class="col pb-0 pt-3">{{ t(autoban.model,'model_title') }}</div>
          </div>
        </td>
      </tr>
      <tr>
        <td class="py-1"> Generation </td>
        <td class="py-1" v-for="(autoban,index) in autobansOptions.data" v-show="(!index && type === 'diff' && autobansOptions.data.length === 2) || (!index && type === 'diff' && autobansOptions.data.length === 1) || type !== 'diff'">
          {{ t(autoban.type,'type_title') }}
        </td>
      </tr>
      <tr>
        <td class="py-1"> Year </td>
        <td class="py-1" v-for="(autoban,index) in autobansOptions.data" v-show="(!index && type === 'diff' && autobansOptions.data.length === 2) || (!index && type === 'diff' && autobansOptions.data.length === 1) || type !== 'diff'">
          {{ t(autoban.year,'year_number') }}
        </td>
      </tr>
    </tbody>
    <tbody>
      <template v-for="classOption in optionTree.data" :key="classOption.id">
        <tr>
          <td :colspan="autobansOptions.data.length+1" class="bg-blue-darker text-white py-1">
            {{ t(classOption,'option_class_title') }}
          </td>
        </tr>
        <template v-for="subClass in classOption.sub_classes" :key="subClass.id">
          <tr v-show="eliminateDuplicates(autobansOptions.data.filter(x=>x.pivots.map(x=>x.category.option_category_title)).map(x=>x.pivots.filter(z=>+z.category.option_sub_class_id===+subClass.id).filter(z=>z.category.input_type==='multiple select').map(z=>z.options).flat(1)).flat(1).map(x=>x.option_title)).length || eliminateDuplicates(autobansOptions.data.filter(x=>x.pivots.map(x=>x.category.option_category_title)).map(x=>x.pivots.filter(z=>+z.category.option_sub_class_id===+subClass.id).filter(z=>z.category.input_type!=='multiple select').map(z=>z.category)).flat(1).map(x=>x.option_category_title)).length">
            <td :colspan="autobansOptions.data.length+1" class="py-0">
              <u class="h4">
                {{ t(subClass,'option_sub_class_title') }}
              </u>
            </td>
<!--
            <td :colspan="autobansOptions.data.length+1" class="bg-azure-dark text-white py-0">
              {{ t(subClass,'option_sub_class_title') }} - {{ subClass.id }}
            </td>
-->
          </tr>
          <template v-for="category in subClass.option_categories.filter(x=>['select','number','text'].includes(x.input_type))" :key="category.id">
            <tr v-if="autobansOptions.data.filter(x=>x.pivots.map(x=>x.category.option_category_title).includes(category.option_category_title)).length"
                v-show="(type === 'diff' && (autobansOptions.data.filter(x=>x.pivots.map(x=>x.category.option_category_title).includes(category.option_category_title)).length === 1 || eliminateDuplicates(autobansOptions.data.filter(x=>x.pivots.map(x=>x.category.option_category_title).includes(category.option_category_title)).map(x=>x.pivots).flat(1).filter(x=>category.id===x.category.id).map(x=>x.option)).length)) || type !== 'diff'">
              <td class="py-1"> {{ t(category,'option_category_title') }} </td>
              <td class="py-1" v-for="({pivots},index) in autobansOptions.data" v-show="(!index && type === 'diff' && autobansOptions.data.length === 2) || (!index && type === 'diff' && autobansOptions.data.length === 1) || type !== 'diff'">
                <span v-if="pivots.filter(x=> +x.category.id === +category.id)[0]">
                  {{ t(pivots.filter(x=> +x.category.id === +category.id)[0],'option') || t(pivots.filter(x=> +x.category.id === +category.id)[0].options[0],'option_title') }}
                </span>
                <span v-else class="fa fa-remove text-danger"></span>
              </td>
            </tr>
          </template>
          <template v-for="category in subClass.option_categories.filter(x=>!['select','number','text'].includes(x.input_type))">
            <tr v-show="autobansOptions.data.filter(x=>x.pivots.map(x=>x.category.option_category_title).includes(category.option_category_title)).length&& eliminateDuplicates(autobansOptions.data.map(x=> x.pivots.filter(x=>x.category.option_category_title === category.option_category_title)[0] ? x.pivots.filter(x=>x.category.option_category_title === category.option_category_title)[0].options : []).map(x=>x.map(z=>z.option_title)[0])).length">
              <td colspan="2" class="py-1 text-start">
                <u class="h5">{{ t(category,'option_category_title') }}</u>
              </td>
<!--
              <td class="py-1 bg-primary text-white">
                {{ t(category,'option_category_title') }}
              </td>
-->
            </tr>
            <template v-for="option in category.options" :key="option.id" >
              <tr v-if="JSON.stringify(autobansOptions.data.map(x=> x.pivots.filter(x=>x.category.option_category_title === category.option_category_title)[0] ? x.pivots.filter(x=>x.category.option_category_title === category.option_category_title)[0].options.filter(x=> +x.id === +option.id) : false)).includes(option.option_title)"
                  v-show="(type === 'diff' && autobansOptions.data.map(x=> x.pivots.filter(x=>x.category.option_category_title === category.option_category_title)[0] ? x.pivots.filter(x=>x.category.option_category_title === category.option_category_title)[0].options.filter(x=> +x.id === +option.id) : []).filter(x=>x.length).length === 1) || type !== 'diff'">
                <td class="py-1"> {{ t(option,'option_title') }} </td>
                <td class="py-1" v-for="({pivots},index) in autobansOptions.data" v-show="(!index && type === 'diff' && autobansOptions.data.length === 2) || (!index && type === 'diff' && autobansOptions.data.length === 1) || type !== 'diff'">
                  <span v-if="pivots.filter(x=>x.category.option_category_title === category.option_category_title)[0] && pivots.filter(x=>x.category.option_category_title === category.option_category_title)[0].options.filter(x=> +x.id === +option.id).length" class="fa fa-check text-success"></span>
                  <span v-else class="fa fa-remove text-danger"></span>
                </td>
              </tr>
            </template>
          </template>
        </template>
      </template>
    </tbody>
  </table>
</template>

<script setup>
import Image from "primevue/image";
import {onMounted} from "vue";
const props = defineProps({
  autobansOptions: Object,
  optionTree: Object,
  type: {
    type: String,
    default: "Comparison"
  }
})
const emits = defineEmits(['removeFromComparison'])
const findDuplicates = (arr) => {
  let sorted_arr = arr.slice().sort(); // You can define the comparing function here.
  let results = [];
  for (let i = 0; i < sorted_arr.length - 1; i++) {
    /*if (sorted_arr[i + 1].option_title === sorted_arr[i].option_title) {*/
    if (sorted_arr[i + 1] === sorted_arr[i]) {
      results.push(sorted_arr[i]);
    }
  }
  return results;
}
const eliminateDuplicates = (arr) => {
  let out = []
  for (let i = 0; i < arr.length; i++) {
    const v = arr[i];
    if ( !findDuplicates(arr).includes(v) ) {
      out.push(v)
    }
  }
  return out;
}
</script>
<style scoped>
td {
  margin: 0 !important;
}
</style>
