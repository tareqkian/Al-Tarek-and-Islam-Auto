<template>
  <Loading v-if="optionTree.loading" />
  <div v-else class="row">
    <div class="col-12 text-center mb-5 fw-bold">
      {{ (!fullAutoban.data.model || `${t(fullAutoban.data.model.brand,'brand_title')} - ${t(fullAutoban.data.model,'model_title')} - ${t(fullAutoban.data.type,'type_title')} - ${t(fullAutoban.data.year,'year_number')}`) }}
    </div>
    <div v-for="classes in optionTree.data" class="col-12">
      <h3 class="text-center bg-azure-dark py-3">
        {{ t(classes,'option_class_title') }}
      </h3>
      <div class="row">
        <div v-for="subClass in classes.sub_classes" class="col-6">
          <h5 class="text-center">
            {{ t(subClass,'option_sub_class_title') }}
          </h5>
          <div class="row px-3">
            <div v-for="category in subClass.option_categories.filter(x=> typeof optionModel[x.id] !== 'undefined' && optionModel[x.id].toString().length)"
                 class="col-12 py-1 my-1 border-bottom"
                 :class="[Array.isArray(optionModel[category.id]) || 'd-flex justify-content-between']">
              <span :class="[!Array.isArray(optionModel[category.id]) || 'border-bottom pb-1']">
                {{ t(category,'option_category_title') }}
                <span class="text-muted fw-bold mx-1">
                  {{ category.abbreviation }}
                </span>
              </span>
              <span v-if="!Array.isArray(optionModel[category.id])">
                {{ t(category.options.filter(x=>+x.id === +optionModel[category.id])[0],'option_title') || t(optionModel,category.id) }}
              </span>
              <ul v-else class="list-style-disc mt-2">
                <li v-for="option in category.options.filter(x=> optionModel[category.id].includes(x.id) )">
                  {{ t(option,'option_title') }}
                  <span class="text-muted fw-bold mx-1">
                    {{ option.abbreviation }}
                  </span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {computed, onMounted} from "vue";
import {useOptionStore} from "../../store/Options";
import Loading from "../Loading.vue";
import {useAutobanStore} from "../../store/Autoban";

const props = defineProps({
  'optionSubClasses': Object,
  'optionCategories': Object,
  'optionModel': Object,
  'autoban': {
    default: null,
    type: Object
  }
})
const OptionStore = useOptionStore()
const AuotbanStore = useAutobanStore();


const optionTree = computed(()=>OptionStore.optionTree)
const fullAutoban = computed(()=>AuotbanStore.fullAutoban)
onMounted(async()=>{
  OptionStore.initOptionTree()
  if ( props.autoban ) {
    AuotbanStore.initAutoban(props.autoban)
  }
})
</script>

