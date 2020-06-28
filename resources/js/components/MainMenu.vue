<template>
    <aside class="menu">
        <p class="menu-label">
            Account
        </p>
        <ul class="menu-list">
            <li><router-link tag="a" to="/backend/account/admin" class="text-decoration-none"><b-icon icon="account-tie"/> Administrator</router-link></li>
            <li><router-link tag="a" to="/backend/account/farmer" class="text-decoration-none"><b-icon icon="barn"/> Farmer</router-link></li>
            <li><router-link tag="a" to="/backend/account/customer" class="text-decoration-none"><b-icon icon="account-multiple"/> Customer</router-link></li>
        </ul>
        <p class="menu-label">
            Livestock
        </p>
        <ul class="menu-list">
            <li><router-link tag="a" to="/backend/livestock/product" class="text-decoration-none"><b-icon icon="package-variant"/> Product</router-link></li>
            <li><router-link tag="a" to="/backend/livestock/customer" class="text-decoration-none"><b-icon icon="file-account"/> Customer</router-link></li>
        </ul>
        <p class="menu-label">
            Transaction
        </p>
        <ul class="menu-list">
            <li><router-link tag="a" to="/backend/transaction/view" class="text-decoration-none"><b-icon icon="poll"/> Transaction</router-link></li>
            <li><router-link tag="a" to="/backend/transaction/confirmation" class="text-decoration-none"><b-icon icon="check-all"/> Confirmation</router-link></li>
            <li><router-link tag="a" to="/backend/transaction/withdrawal" class="text-decoration-none"><b-icon icon="cash-refund"/> Withdrawal</router-link></li>
        </ul>
        <p class="menu-label">
            Transaction
        </p>
        <ul class="menu-list">
            <li><router-link tag="a" to="/backend/crm/notification" class="text-decoration-none"><b-icon icon="chat"/> Notification</router-link></li>
            <li><router-link tag="a" to="/backend/crm/voucher" class="text-decoration-none"><b-icon icon="ticket-percent"/> Voucher</router-link></li>
            <li><router-link tag="a" to="/backend/crm/ticketing" class="text-decoration-none"><b-icon icon="alert-octagon"/> Ticketing</router-link></li>
        </ul>
    </aside>
</template>
