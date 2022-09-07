<template>
  <Loading v-if="options.loading" />
  <div v-else class="accordion w-75 mx-auto" id="accordionFlush">
    <draggable
      v-model="options.data"
      group="option_class"
      tag="div"
      class="m-0 p-0 card rounded"
      item-key="id">
      <template #item="{element}">
        <div class="py-3 border-bottom">
          <div class="row justify-content-center px-3"
               data-bs-toggle="collapse"
               :data-bs-target="`#flush-collapse-${element.option_class_title}`">
            <div class="col-12 text-center">
              <span class="d-block mt-2 font-weight-bold">{{ t(element,'option_class_title') }}</span>
            </div>
          </div>
          <div :id="`flush-collapse-${element.option_class_title}`" class="accordion-collapse collapse" data-bs-parent="#accordionFlush">
            <div class="accordion-body">

              <div class="accordion" :id="`accordionFlush${element.option_class_title}`">
                <draggable
                  v-model="element.children"
                  group="option_sub_class"
                  tag="div"
                  class="m-0 p-0 row"
                  item-key="id">
                  <template #item="{element}">
                    <div class="col-6 py-3 border-bottom">
                      <div class="row justify-content-center px-3"
                           data-bs-toggle="collapse"
                           :data-bs-target="`#flush-collapse-${element.option_sub_class_title}`">
                        <div class="col-12 text-center">
                          <span class="d-block mt-2 font-weight-bold">{{ t(element,'option_sub_class_title') }}</span>
                        </div>
                      </div>
                      <div :id="`flush-collapse-${element.option_sub_class_title}`" class="accordion-collapse collapse" :data-bs-parent="`#accordionFlush${element.option_class_title}`">
                        <div class="accordion-body">








                          <draggable
                            v-model="element.children"
                            group="option_category"
                            tag="div"
                            class="row"
                            item-key="id">
                            <template #item="{element}">
                              <div class="col-12 text-center py-2 border-bottom border-top">
                                {{ t(element,'option_category_title') }}
                              </div>
                            </template>
                          </draggable>




                        </div>
                      </div>
                    </div>
                  </template>
                </draggable>
              </div>

            </div>
          </div>
        </div>
      </template>
    </draggable>
  </div>

</template>

<script setup>
import draggable from 'vuedraggable'
import { ref } from "vue";
import Loading from '../../Loading.vue'

const props = defineProps({
  parentClass: String,
  options: Object
})

</script>
