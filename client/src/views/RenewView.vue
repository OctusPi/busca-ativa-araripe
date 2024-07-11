<script setup>
import { inject, ref, reactive } from 'vue';
import useVuelidate from '@vuelidate/core';
import { helpers, required, minLength, sameAs } from '@vuelidate/validators';
import auth from '@/stores/auth';
import http from '@/services/http';
import forms from '@/services/forms';
import notifys from '@/utils/notifys';

const app = inject('app');
const emit = defineEmits(['callAlert']);

const letter_lower = helpers.withMessage('A senha deve conter pelo menos uma letra minúscula.', helpers.regex(/(?=.*[a-z])/));
const letter_capital = helpers.withMessage('A senha deve conter pelo menos uma letra maiúscula.', helpers.regex(/(?=.*[A-Z])/));
const has_number = helpers.withMessage('A senha deve conter pelo menos um número.', helpers.regex(/(?=.*\d)/));
const special_char = helpers.withMessage('A senha deve conter pelo menos um caractere especial.', helpers.regex(/(?=.*[!@#$%^&*(),.?":{}|<>])/));
const min_length = helpers.withMessage('A senha deve ter no mínimo 8 caracteres.', minLength(8));
const required_field = helpers.withMessage('Este campo é obrigatório.', required);

const page = reactive({
	new_password: '',
	confirm_new_password: '',
	showNewPassword: false,
	showConfirmNewPassword: false,
});

const rules = {
	new_password: {
		required: required_field,
		minLength: min_length,
		letter_lower,
		letter_capital,
		has_number,
		special_char,
		$autoDirty: true,
	},
	confirm_new_password: {
		required: required_field,
		// sameAs: helpers.withMessage('As senhas devem ser iguais.', sameAs(() => page.new_password)),
		confirmPassword: helpers.withMessage('As senhas devem ser iguais.', (value) => value === page.new_password),
		$autoDirty: true
	},
};
const v$ = useVuelidate(rules, page, { $lazy: true });

const isPasswordFieldTouched = ref(false);

const handlePasswordInput = () => {
	v$.value.$validate();
	if (!isPasswordFieldTouched.value) {
		isPasswordFieldTouched.value = true;
	}
};

const togglePasswordVisibility = (field) => {
	page[field] = !page[field];
};

function submitForm() {
	v$.value.$validate();

	if (!v$.value.$error) {
		alert('Sucesso!');
	} else {
		if (v$.value.new_password.$invalid) {
			console.log('Erro no campo nova senha:', v$.value.new_password.$errors);
		}
		if (v$.value.confirm_new_password.$invalid) {
			console.log('Erro no campo confirmar nova senha:', v$.value.confirm_new_password.$errors);
		}
		alert('Erro!');
	}
}

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
					<label for="new_password" class="form-label">Nova Senha<span class="text-danger">*</span></label>
					<div class="position-relative">
						<input :type="page.showNewPassword ? 'text' : 'password'" name="new_password"
							class="form-control" id="new_password" v-model="page.new_password">
						<button type="button" class="btn-eye" @click="togglePasswordVisibility('showNewPassword')">
							<i :class="page.showNewPassword ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
						</button>
					</div>
				</div>

				<div class="mb-2">
					<label for="confirm_new_password" class="form-label">Confirmar Nova Senha<span class="text-danger">*</span></label>
					<div class="position-relative">
						<input :type="page.showConfirmNewPassword ? 'text' : 'password'" name="confirm_new_password"
							class="form-control" id="confirm_new_password" v-model="page.confirm_new_password">
						<button type="button" class="btn-eye"
							@click="togglePasswordVisibility('showConfirmNewPassword')">
							<i :class="page.showConfirmNewPassword ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
						</button>
					</div>
					<span v-if="page.new_password !== page.confirm_new_password" class="text-danger">As senhas devem ser
						iguais.</span>
					<!-- <div v-if="v$.confirm_new_password.$error" class="text-danger">
						<p v-for="error of v$.confirm_new_password.$errors" :key="error.$uid">{{ error.$message }}</p>
					</div> -->

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
					<button type="submit" :disabled="v$.$invalid || !isPasswordFieldTouched"
						:class="{ 'btn-outline-primary': true }" class="btn w-100">
						Cadastrar Nova Senha<i class="bi bi-check2-circle"></i>
					</button>

				</div>
			</form>
			<div class="box-copyr">
				<p class="txt-color-sec small p-0 m-0 text-center">Todos os direitos reservados.</p>
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
}

.position-relative .btn-eye:hover {
	color: #000;
}
</style>
