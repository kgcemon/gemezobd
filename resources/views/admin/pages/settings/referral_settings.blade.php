@extends('admin.layouts.app')


@section('content')
<div class="container">
    <h2>Referral Settings</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('admin.referral.settings.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Level 1 Bonus ($)</label>
            <input type="number" step="0.01" name="level_1_bonus" value="{{ $settings->level_1_bonus }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Other 19 Levels Bonus ($)</label>
            <input type="number" step="0.01" name="other_levels_bonus" value="{{ $settings->other_levels_bonus }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Reward Tokens for 20 Active Referrals</label>
            <input type="number" name="reward_tokens" value="{{ $settings->reward_tokens }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Active Referrals Required for Reward</label>
            <input type="number" name="reward_for_users" value="{{ $settings->reward_for_users }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="withdraw_charge">Withdrawal bonus (20 level)</label>
            <input type="number" step="0.01" name="withdraw_charge" value="{{ $settings->withdraw_charge }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection