<script>
import { inject, onMounted, ref, reactive, computed } from 'vue';
import auth from '@/stores/auth';
import http from '@/services/http';
import forms from '@/services/forms';
import notifys from '@/utils/notifys';
import useVuelidate from '@vuelidate/core';
import { helpers, required, minLength, sameAs } from '@vuelidate/validators'


const letter_lower = helpers.regex(/(?=.*[a-z])/);
const letter_capital = helpers.regex(/(?=.*[A-Z])/);
const has_number = helpers.regex(/(?=.*\d)/);
const special_char = helpers.regex(/(?=.*[!@#$%^&*(),.?":{}|<>])/);

export default {
	name: 'RenewView',
	setup() {

		const togglePasswordVisibility = (field) => {
			state[field] = !state[field];
		};

		const state = reactive({
			new_password: '',
			confirm_new_password: '',
			showNewPassword: false,
			showConfirmNewPassword: false,
		});

		const rules = computed(() => ({
			new_password: {
				required,
				minLength: minLength(8),
				letter_lower,
				letter_capital,
				has_number,
				special_char,
			},
			confirm_new_password: {
				required,
				sameAs: sameAs(() => state.new_password)
			},
		}));

		const v$ = useVuelidate(rules, state);

		const isPasswordFieldTouched = ref(false);

		const handlePasswordInput = () => {
			if (!isPasswordFieldTouched.value) {
				isPasswordFieldTouched.value = true;
			}
		};

		const app = inject('app');

		return {
			state,
			v$,
			togglePasswordVisibility,
			isPasswordFieldTouched,
			handlePasswordInput,
			app
		};
	},
	methods: {
		submitForm() {
			// TODO
			// 
			this.v$.$validate();
			if (!this.v$.$error) {
				alert('Sucesso!');
			} else {
				alert('Erro!');
			}
		},
	},
};
</script>


<template>
	<main class="container-back-box d-flex justify-content-center align-items-center">
		<div class="box p-5 rounded-4 shadow-sm container-box">
			<header class="d-lg-flex align-items-center text-center text-lg-start mb-4">
				<img src="../assets/imgs/logo.svg" class="mb-3 mb-lg-0" width="55">
				<div>
					<h1 class="m-0 p-0 ms-0 ms-lg-2 sistem-title-box">{{ app.name }}</h1>
					<p class="m-0 p-0 text-color-sec small ms-0 ms-lg-2">{{ app.desc }}</p>
				</div>
			</header>

			<form class="row g-3" @submit.prevent="submitForm" @input="handlePasswordInput" method="post">
				<div class="mb-2">
					<label for="new_password" class="form-label">Nova Senha</label>
					<div class="position-relative">
						<input :type="state.showNewPassword ? 'text' : 'password'" name="new_password"
							class="form-control" id="new_password" v-model="state.new_password">
						<button type="button" class="btn-eye" @click="togglePasswordVisibility('showNewPassword')">
							<i :class="state.showNewPassword ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
						</button>
					</div>
				</div>

				<div class="mb-2">
					<label for="confirm_new_password" class="form-label">Confirmar Nova Senha</label>
					<div class="position-relative">
						<input :type="state.showConfirmNewPassword ? 'text' : 'password'" name="confirm_new_password"
							class="form-control" id="confirm_new_password" v-model="state.confirm_new_password">
						<button type="button" class="btn-eye"
							@click="togglePasswordVisibility('showConfirmNewPassword')">
							<i :class="state.showConfirmNewPassword ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
						</button>
					</div>
					<span v-if="state.confirm_new_password && state.new_password !== state.confirm_new_password"
						class="text-danger">As senhas devem ser iguais.</span>
				</div>

				<h3 v-if="isPasswordFieldTouched"><i class="bi bi-info-circle"></i> A nova senha deve conter: </h3>
				<div id="message" v-if="isPasswordFieldTouched">
					<p id="min_length">
						<i
							:class="{ 'bi bi-check2 text-success': !v$.new_password.minLength.$invalid, 'bi bi-x-lg text-danger': v$.new_password.minLength.$invalid }"></i>
						Mínimo de 8 caracteres
					</p>
					<p id="letter_lower">
						<i
							:class="{ 'bi bi-check2 text-success': !v$.new_password.letter_lower.$invalid, 'bi bi-x-lg text-danger': v$.new_password.letter_lower.$invalid }"></i>
						Letras minúsculas
					</p>
					<p id="letter_capital">
						<i
							:class="{ 'bi bi-check2 text-success': !v$.new_password.letter_capital.$invalid, 'bi bi-x-lg text-danger': v$.new_password.letter_capital.$invalid }"></i>
						Letras maiúsculas
					</p>
					<p id="has_number">
						<i
							:class="{ 'bi bi-check2 text-success': !v$.new_password.has_number.$invalid, 'bi bi-x-lg text-danger': v$.new_password.has_number.$invalid }"></i>
						Números
					</p>
					<p id="special_char">
						<i
							:class="{ 'bi bi-check2 text-success': !v$.new_password.special_char.$invalid, 'bi bi-x-lg text-danger': v$.new_password.special_char.$invalid }"></i>
						Caracteres especiais (ex: @#$%*&!)
					</p>
				</div>

				<div class="mb-4">
					<button type="submit" :class="{ 'btn-outline-primary': true }" class="btn w-100">Cadastrar Nova
						Senha<i class="bi bi-check2-circle"></i></button>
				</div>
			</form>
			<div class="box-copyr">
				<p class="txt-color-sec small p-0 m-0 text-center">Todos os direitos reservados.</p>
				<!-- <p class="txt-color-sec small p-0 m-0 text-center">{{ app.copy }}&copy;</p> -->
			</div>
		</div>
	</main>
</template>

<style>
.icon-user {
	font-size: 3rem;
}

.position-relative .btn-eye {
	border: none;
	background: transparent;
	position: absolute;
	right: 10px;
	top: 50%;
	transform: translateY(-50%);
	padding: 0;
	cursor: pointer;
	color: #6c757d;
	/* font-size: 5rem; */
	/* Aumentar o tamanho do ícone */
}

.position-relative .btn-eye:hover {
	color: #000;
}
</style>