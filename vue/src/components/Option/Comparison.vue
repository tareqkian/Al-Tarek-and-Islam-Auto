<template>
  <div class="dropdown header-notify">
    <a class="nav-link icon"
       @click="startComparison"
       data-bs-toggle="sidebar-right"
       data-bs-target=".autoban-comparison">
      <i class="fa fa-car header-icon"></i>
      <span v-if="autobans.length" class="badge badge-primary side-badge"> {{ autobans.length }} </span>
    </a>
  </div>

  <div class="sidebar sidebar-right autoban-comparison sidebar-animate">
    <div class="card-header border-bottom pb-5">
      <h4 class="card-title"> Comparison </h4>
      <div class="card-options">
        <a  href="javascript:void(0);"
            class="btn btn-sm btn-icon btn-light text-primary"
            data-bs-toggle="sidebar-right"
            data-bs-target=".autoban-comparison">
          <i class="feather feather-x"></i>
        </a>
      </div>
    </div>
    <div class="card-body comparison-body">
      <table v-if="autobansOptions.length" class="table table-hover table-striped table-bordered table-center">
        <tbody>
          <tr>
            <td width="30%"> Model </td>
            <td :width="`${70/autobansOptions.length}%`" v-for="autoban in autobansOptions" class="position-relative">
              <span @click="removeFromComparison(autoban)"
                    class="fa fa-trash-o text-danger position-absolute top-0 end-0 z-index-10 m-1"></span>
              <div class="row p-0">
                <div class="col-4 d-flex justify-content-center align-items-center">
                  <Image :src="autoban.model.brand.brand_image" imageClass="w-100" />
                </div>
                <div class="col-8 d-flex justify-content-center align-items-center">
                  <Image :src="autoban.model.model_image" imageClass="w-100" />
                </div>
                <div class="col pb-0 pt-3">{{ t(autoban.model,'model_title') }}</div>
              </div>
            </td>
          </tr>
          <tr>
            <td> Generation </td>
            <td v-for="autoban in autobansOptions">
              {{ t(autoban.type,'type_title') }}
            </td>
          </tr>
          <tr>
            <td> Year </td>
            <td v-for="autoban in autobansOptions">
              {{ t(autoban.year,'year_number') }}
            </td>
          </tr>
        </tbody>

        <tbody v-for="classOption in optionTree.data" :key="classOption.id">
          <tr>
            <td :colspan="autobansOptions.length+1" class="bg-blue-darker py-1">
              {{ t(classOption,'option_class_title') }}
            </td>
          </tr>

          <template v-for="subClass in classOption.sub_classes" :key="subClass.id">
            <tr>
              <td :colspan="autobansOptions.length+1" class="bg-azure-dark py-0">
                {{ t(subClass,'option_sub_class_title') }}
              </td>
            </tr>
            <tr v-for="category in subClass.option_categories.filter(x=>['select','number','text'].includes(x.input_type))">
              <td> {{ t(category,'option_category_title') }} </td>
              <td v-for="{pivots} in autobansOptions">
                <span v-if="pivots.filter(x=> +x.category.id === +category.id)[0]">
                  {{ t(pivots.filter(x=> +x.category.id === +category.id)[0],'option') || t(pivots.filter(x=> +x.category.id === +category.id)[0].options[0],'option_title') }}
                </span>
                <span v-else class="fa fa-remove text-danger"></span>
              </td>
            </tr>

            <template v-for="category in subClass.option_categories.filter(x=>!['select','number','text'].includes(x.input_type))">
              <tr>
                <td class="py-1 bg-primary"> {{ t(category,'option_category_title') }} </td>
              </tr>
              <tr v-for="option in category.options">
                <td> {{ t(option,'option_title') }} </td>
                <td v-for="{pivots} in autobansOptions">
                  <span v-if="pivots[0] && pivots[0].options.filter(x=> +x.id === +option.id).length" class="fa fa-check text-success"></span>
                  <span v-else class="fa fa-remove text-danger"></span>
                </td>
              </tr>
            </template>


          </template>

        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import Image from "primevue/image";
import {useComparisonStore} from "../../store/AutobanOptionComparison";
import {computed, onActivated, onMounted, onRenderTriggered, onUpdated} from "vue";
import {useOptionStore} from "../../store/Options";

const ComparisonStore = useComparisonStore()
const OptionStore = useOptionStore();
const autobans = computed(()=>ComparisonStore.autobans)
const autobansOptions = computed(()=>ComparisonStore.autobansOptions)
const optionTree = computed(()=>OptionStore.optionTree)

const removeFromComparison = (autoban)=>{
  ComparisonStore.removeAutobanFromComparison(autoban)
}

const startComparison = async ()=>{
  try {
    /*if ( !optionTree.value.data.length )*/
    OptionStore.initOptionTree()
    ComparisonStore.initComparison()
  } catch (e) {
    console.log(e)
  }
}

</script>

<style lang="scss" scoped>
.autoban-comparison {
  width: 100% !important;
}
@media (min-width: 768px) {
  .autoban-comparison {
    width: 50% !important;
  }
}
.comparison-body{
  max-height: calc(100% - 83px);
  overflow: auto;
}
</style>
