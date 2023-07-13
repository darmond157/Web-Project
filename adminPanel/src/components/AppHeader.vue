<template>
	<CHeader position="sticky" class="mb-4">
		<CContainer fluid>
			<CHeaderToggler class="ps-1" @click="$store.commit('toggleSidebar')">
				<CIcon icon="cil-menu" size="lg" />
			</CHeaderToggler>
			<CHeaderNav>
				<CNavItem>
					<CNavLink href="#" @click="logout">
						<CIcon :icon="cilXCircle" size="xl" />
					</CNavLink>
				</CNavItem>
			</CHeaderNav>
		</CContainer>
	</CHeader>
</template>

<script>
	import { cilXCircle } from "@coreui/icons";
	import axios from "axios";
	export default {
		name: "AppHeader",
		setup() {
			return {
				cilXCircle,
			};
		},
		methods: {
			logout() {
				axios
					.post("localhost:8000/api/auth/logout")
			},
		},
	};
</script>
