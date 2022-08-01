<template>
  <div class="page responsive-log login-bg">
    <div class="page-single">
      <div class="container">
        <div class="row">
          <div class="col mx-auto">
            <div class="row justify-content-center">
              <div class="col-md-8 col-lg-6 col-xl-4 col-xxl-4">
                <div class="card my-5">
                  <div class="p-4 pt-6 text-center">
                    <h1 class="mb-2">Login</h1>
                    <p class="text-muted">Sign In to your account</p>
                  </div>
                  <form class="card-body pt-3" @submit.prevent="handleLogin">

                    <div class="form-group">
                      <label class="form-label">E-Mail</label>
                      <div class="input-group mb-4">
                        <div class="input-group">
                          <a href="javascript:void(0)" class="input-group-text">
                            <i class="fe fe-mail" aria-hidden="true"></i>
                          </a>
                          <input :readonly="showPassword" class="form-control" placeholder="Email" v-model="login.email" :class="[errors.email ? 'is-invalid' : '']">
                          <span class="input-group-append">
                            <button @click="checkEmail" :disabled="showPassword" class="btn btn-primary-transparent" :class="[!checkLoading || 'btn-loading']" type="button">
                              <i class="fa fa-arrow-right"></i>
                            </button>
                          </span>
                          <div class="invalid-feedback">
                            <ul>
                              <li v-for="err in errors.email" :key="err"> {{err}} </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div v-if="showPassword" class="form-group">
                      <label class="form-label">Password</label>
                      <div class="input-group mb-4">
                        <div class="input-group" id="Password-toggle">
                          <a href="javascript:void(0)" tabindex="-1" class="input-group-text">
                            <i class="fe fe-eye-off" aria-hidden="true"></i>
                          </a>
                          <input class="form-control" type="password" placeholder="Password" v-model="login.password" :class="[errors.password ? 'is-invalid' : '']">
                          <div class="invalid-feedback">
                            <ul>
                              <li v-for="err in errors.password" :key="err"> {{err}} </li>
                            </ul>
                          </div>
                        </div>
                      </div>

                      <label v-if="!emailConfirmed" class="form-label">Password Confirmation</label>
                      <div v-if="!emailConfirmed" class="input-group mb-4">
                        <div class="input-group" id="Password-toggle">
                          <a href="javascript:void(0)" tabindex="-1" class="input-group-text">
                            <i class="fe fe-eye-off" aria-hidden="true"></i>
                          </a>
                          <input class="form-control" type="password" placeholder="Password Confirmation" v-model="login.password_confirmation" :class="[errors.password_confirmation ? 'is-invalid' : '']">
                          <div class="invalid-feedback">
                            <ul>
                              <li v-for="err in errors.password_confirmation" :key="err"> {{err}} </li>
                            </ul>
                          </div>
                        </div>
                      </div>

                    </div>

                    <div class="form-group">
                      <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input"
                               name="example-checkbox1" value="option1" v-model="login.remember">
                        <span class="custom-control-label">Remember me</span>
                      </label>
                    </div>
                    <div class="submit">
                      <button :disabled="!login.email.length || (!login.password.length || (!emailConfirmed ? !login.password_confirmation.length : false))" class="btn btn-primary btn-block" :class="[!loginLoading || 'btn-loading']" type="submit">
                        Login
                      </button>
                    </div>
                    <div class="text-center mt-3">
                      <p class="mb-2">
                        <a href="javascript:void(0);">
                          Forgot Password
                        </a>
                      </p>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuth } from "/src/store/Auth";
import { useRouter } from "vue-router"
import { ref } from "vue";
const { push } = useRouter();
const errors = ref({})
const login = ref({
  email: '',
  password: '',
  password_confirmation: '',
  remember: false,
})
const checkLoading = ref(false)
const emailConfirmed = ref(false)
const showPassword = ref(false)
const confirmedUser = ref({});
const checkEmail = async()=>{
  try {
    checkLoading.value = true
    errors.value = {}
    const authStore = useAuth()
    const {status,data} = await authStore.checkEmailExists(login.value)
    confirmedUser.value = data
    emailConfirmed.value = status
    showPassword.value = true
    checkLoading.value = false
  } catch (e) {
    checkLoading.value = false
    emailConfirmed.value = false
    errors.value = e
  }
}

const loginLoading = ref(false);
const handleLogin = async()=>{
  try {
    loginLoading.value = true
    const authStore = useAuth()
    errors.value = {}
    if ( login.value.password_confirmation ) {
      login.value.confirmedUser = confirmedUser.value
      await authStore.register(login.value)
    } else {
      await authStore.login(login.value)
    }
    await push({name: 'Dashboard'})
    loginLoading.value = false
  } catch (e) {
    loginLoading.value = false
    errors.value = e
  }
}
</script>
