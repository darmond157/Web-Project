<template>
	<CSidebar
		position="fixed"
		:unfoldable="sidebarUnfoldable"
		:visible="sidebarVisible"
		@visible-change="(event) =>$store.commit({ type: 'updateSidebarVisible',value: event,})"	
	>
		<AppSidebarNav style="margin-top:100px;"/>
	</CSidebar>
</template>

<script>
	import { computed } from "vue";
	import { useStore } from "vuex";
	import { AppSidebarNav } from "../utils/AppSidebarNav";

	export default {
		name: "AppSidebar",
		components: {
			AppSidebarNav,
		},
		setup() {
			const store = useStore();
			return {
				sidebarUnfoldable: computed(() => store.state.sidebarUnfoldable),
				sidebarVisible: computed(() => store.state.sidebarVisible),
			};
		},
	};
</script>
