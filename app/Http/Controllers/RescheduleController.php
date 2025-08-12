<?php

use Illuminate\Http\Request;
use App\Models\Bimbingan;
use Illuminate\Support\Facades\Auth;

class RescheduleController
{
  public function accept(Request $r, Bimbingan $b)
  {
    // validasi kepemilikan
    abort_unless($b->student_id === Auth::id(), 403);

    // pastikan masih PENDING & belum kadaluarsa
    if ($b->reschedule_status !== 'PENDING' || $b->reschedule_expires_at?->isPast()) {
      return back()->with('error','Usulan tidak valid/expired.');
    }

    // terapkan jadwal baru
    $b->scheduled_at = $b->reschedule_suggested_at;
    $b->reschedule_status = 'ACCEPTED';
    $b->reschedule_suggested_at = null;
    $b->reschedule_expires_at = null;
    $b->save();

    // kabari dosen
    $b->lecturer->notify(new \App\Notifications\RescheduleAccepted($b));

    return back()->with('success','Perubahan jadwal disetujui.');
  }

  public function reject(Request $r, Bimbingan $b)
  {
    abort_unless($b->student_id === Auth::id(), 403);

    if ($b->reschedule_status !== 'PENDING' || $b->reschedule_expires_at?->isPast()) {
      return back()->with('error','Usulan tidak valid/expired.');
    }

    $reason = $r->string('reason')->toString();

    // tandai ditolak (jadwal lama tetap)
    $b->reschedule_status = 'REJECTED';
    $b->reschedule_reject_reason = $reason;
    $b->reschedule_suggested_at = null;
    $b->reschedule_expires_at = null;
    $b->save();

    $b->lecturer->notify(new \App\Notifications\RescheduleRejected($b, $reason));

    return back()->with('success','Perubahan jadwal ditolak.');
  }
}
