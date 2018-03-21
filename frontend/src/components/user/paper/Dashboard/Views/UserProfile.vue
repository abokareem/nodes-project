<template>
    <div class="row">
        <!--<div class="col-lg-4 col-md-5">
            <user-card>

            </user-card>
            <members-card>

            </members-card>
        </div>-->
        <div class="col-lg-12 col-md-12">
            <edit-profile-form>

            </edit-profile-form>
        </div>
        <div v-if="$store.getters['user/get'].email_confirmed" class="col-lg-12 col-md-12">
            <activate-two-fa>

            </activate-two-fa>
        </div>
    </div>
</template>
<script>
import EditProfileForm from './UserProfile/EditProfileForm.vue'
import UserCard from './UserProfile/UserCard.vue'
import MembersCard from './UserProfile/MembersCard.vue'
import ActivateTwoFa from './UserProfile/ActivateTwoFa.vue'
export default {
  components: {
    EditProfileForm,
    UserCard,
    MembersCard,
    ActivateTwoFa
  }
}

</script>
<style lang="scss" scoped>
@import "../../../../../assets/sass/bootstrap.css";
@import "../../../../../assets/sass/paper-dashboard.scss";
</style>
