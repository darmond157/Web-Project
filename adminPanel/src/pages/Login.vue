<template>
	<div class="bg-light min-vh-100 d-flex flex-row align-items-center">
		<CContainer>
			<CRow class="justify-content-center">
				<CCol :md="4">
					<CCardGroup>
						<CCard class="p-4">
							<CCardBody>
								<CForm>
									<p v-show="errorMsg !== ''" class="bg bg-danger">
										{{ errorMsg }}
									</p>
									<p v-show="captcha_error !== ''" class="bg bg-danger">
										{{ captcha_error }}
									</p>
									<h1>Login</h1>
									<p class="text-medium-emphasis">Sign In to your account</p>
									<CInputGroup class="mb-3">
										<CInputGroupText>
											<CIcon icon="cil-user" />
										</CInputGroupText>
										<CFormInput placeholder="Username" id="username" />
									</CInputGroup>
									<CInputGroup class="mb-4">
										<CInputGroupText>
											<CIcon icon="cil-lock-locked" />
										</CInputGroupText>
										<CFormInput type="password" placeholder="Password" id="password" />
									</CInputGroup>
									<CRow>
										<Arcaptcha :site_key="site_key" :callback="solved" />
									</CRow>
									<CRow>
										<div>
											<button :disabled="isLoading" style="width:100%;" type="button" id="loginBtn" @click="LoginAuthentication" class="btn btn-outline-primary mt-3 center">
													<span v-if="!isLoading">Login</span>
													<span v-else ><i class="fa fa-circle-o-notch fa-spin"></i>Loading</span>
												</button>
										</div>
									</CRow>
								</CForm>
							</CCardBody>
						</CCard>
					</CCardGroup>
				</CCol>
			</CRow>
		</CContainer>
	</div>
</template>

<script>
import axios from "axios";
import Arcaptcha from "arcaptcha-vue3";
export default {
	name: "Login",
	components: {
		Arcaptcha,
	},
	data() {
		return {
			errorMsg: "",
			site_key: "4nma7h0vqd",
			captcha_token: 0,
			captcha_solved: false,
			captcha_error: '',
			isLoading: false,
		};
	},
	beforeCreate() {
		axios.get(`/sanctum/csrf-cookie`);
	},
	mounted() {
		localStorage.setItem("token", "");
	},
	methods: {
		solved(token) {
			this.captcha_token = token;
			this.captcha_solved = true;
		},

		LoginAuthentication() {

			localStorage.setItem('page', 1)

			if (this.captcha_token !== 0) {
				this.isLoading = true
				axios
					.post('/api/auth/admin', {
						username: document.getElementById('username').value,
						password: document.getElementById('password').value,
						arcaptchaToken: this.captcha_token
					})
					.then(value => {
						if (value.status == 200 && this.captcha_solved) {
							localStorage.setItem('token', value.data.slice(1))
							this.$router.push('/dashboard')
							this.isLoading = false
						}
					})
					.catch((e) => {
						this.errorMsg = e.response.data
						this.isLoading = false
					})
					.finally(() => {
						this.isLoading = false
					})

			}
			else{
				this.errorMsg="Solve the Captcha first"
			}

		}
	}
}
</script>

<style>
#arc-checkbox {
	width: 100% !important;
}
</style>