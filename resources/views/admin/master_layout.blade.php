@php
    $setting = App\Models\Setting::first();
    $hasReservationViewedColumn = \Illuminate\Support\Facades\Schema::hasColumn('reservations', 'admin_viewed_at');
    $today = \Illuminate\Support\Carbon::today();
    $nextThreeDays = \Illuminate\Support\Carbon::today()->addDays(2);
    $threeDaysAgo = \Illuminate\Support\Carbon::now()->subDays(2)->startOfDay();

    $relevantReservationQuery = App\Models\Reservation::query()
        ->where(function ($query) use ($today, $nextThreeDays, $threeDaysAgo) {
            $query->whereBetween('reserve_date', [$today->toDateString(), $nextThreeDays->toDateString()])
                ->orWhere('created_at', '>=', $threeDaysAgo);
        });

    $relevantReservationCount = (clone $relevantReservationQuery)->count();
    $unviewedReservationCount = $hasReservationViewedColumn
        ? (clone $relevantReservationQuery)->whereNull('admin_viewed_at')->count()
        : $relevantReservationCount;
@endphp

@include('admin.header')
<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg custom_click"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-link-lg fullscreen-toggle-btn" id="fullscreenToggleBtn" title="Toggle Fullscreen">
                <span class="fullscreen-icon-bg">
                    <i class="fas fa-expand" id="fullscreenToggleIcon"></i>
                </span>
            </a>
          </li>
          <li class="nav-item reservation-nav-item">
            <a href="javascript:;" class="nav-link nav-link-lg reservation-notification-trigger" id="reservationNotificationTrigger" title="Reservations">
                <span class="reservation-icon-bg">
                    <i class="far fa-calendar-alt"></i>
                </span>
                <span class="reservation-nav-label d-none d-lg-inline"></span>
                <span class="badge badge-danger reservation-count-badge" id="reservationCountBadge" style="{{ $unviewedReservationCount > 0 ? '' : 'display:none;' }}">
                    {{ $unviewedReservationCount > 99 ? '99+' : $unviewedReservationCount }}
                </span>
            </a>
          </li>

          @php
              $header_admin=Auth::guard('admin')->user();
              $defaultProfile = App\Models\BannerImage::whereId('15')->first();
          @endphp
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              @if ($header_admin->image)
              <img alt="image" src="{{ asset($header_admin->image) }}" class="rounded-circle mr-1">
              @else
              <img alt="image" src="{{ asset($defaultProfile->image) }}" class="rounded-circle mr-1">
              @endif
            <div class="d-sm-none d-lg-inline-block">{{ $header_admin->name }}</div></a>
            <div class="dropdown-menu dropdown-menu-right">

              <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> {{__('admin.Profile')}}
              </a>
              <div class="dropdown-divider"></div>
              <a href="{{ route('admin.logout') }}" class="dropdown-item has-icon text-danger" onclick="event.preventDefault();
              document.getElementById('admin-logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> {{__('admin.Logout')}}
              </a>
            {{-- start admin logout form --}}
            <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            {{-- end admin logout form --}}


            </div>
          </li>
        </ul>
      </nav>




      @include('admin.sidebar')

      @yield('admin-content')



      <footer class="main-footer">
        <div class="footer-left">
          {{ $footer->copyright }}
        </div>
        <div class="footer-right">
            {{ env('APP_VERSION') }}
        </div>
      </footer>
    </div>
  </div>

  <div class="modal fade" id="reservationNotificationModal" tabindex="-1" role="dialog" aria-labelledby="reservationNotificationLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content reservation-modal-content">
              <div class="modal-header reservation-modal-header">
                  <h5 class="modal-title" id="reservationNotificationLabel">
                      <i class="far fa-calendar-check mr-2"></i>Reservations Overview
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body reservation-modal-body">
                  <div class="reservation-stats-row">
                      <div class="reservation-stat-card">
                          <div class="reservation-stat-label">Next 3 Days / Last 3 Days</div>
                          <div class="reservation-stat-value" id="reservationRelevantCount">{{ $relevantReservationCount }}</div>
                      </div>
                      <div class="reservation-stat-card">
                          <div class="reservation-stat-label">Unviewed</div>
                          <div class="reservation-stat-value" id="reservationUnviewedCount">{{ $unviewedReservationCount }}</div>
                      </div>
                  </div>
                  <div class="reservation-list-wrap" id="reservationPopupList">
                      <div class="text-center text-muted py-4">Loading reservations...</div>
                  </div>
              </div>
              <div class="modal-footer">
                  <a href="{{ route('admin.reservation') }}" class="btn btn-primary">Go To Reservation Page</a>
              </div>
          </div>
      </div>
  </div>

  <style>
      .fullscreen-toggle-btn {
          padding: 8px 10px !important;
      }

      .fullscreen-icon-bg {
          width: 38px;
          height: 38px;
          border-radius: 11px;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          color: #0f766e;
          background: linear-gradient(135deg, #e9fffb 0%, #cffcf4 100%);
          border: 1px solid #a7f3e9;
          box-shadow: 0 6px 18px rgba(15, 118, 110, 0.16);
      }

      .reservation-notification-trigger {
          position: relative;
          display: inline-flex;
          align-items: center;
          gap: 8px;
          padding: 8px 12px !important;
      }

      .reservation-icon-bg {
          width: 38px;
          height: 38px;
          border-radius: 11px;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          color: #b45309;
          background: linear-gradient(135deg, #fff4e6 0%, #ffd9b3 100%);
          border: 1px solid #ffd0a1;
          box-shadow: 0 6px 18px rgba(217, 119, 6, 0.18);
      }

      .reservation-nav-label {
          font-weight: 600;
          color: #374151;
      }

      .reservation-count-badge {
          position: absolute;
          top: 2px;
          right: 4px;
      }

      .reservation-modal-content {
          border: 1px solid #f4d9bb;
          border-radius: 14px;
          overflow: hidden;
      }

      .reservation-modal-header {
          background: linear-gradient(135deg, #fff7ed 0%, #ffe7ce 100%);
          border-bottom: 1px solid #f4d9bb;
      }

      .reservation-modal-body {
          background: #fffdfa;
      }

      .reservation-stats-row {
          display: grid;
          grid-template-columns: repeat(2, minmax(0, 1fr));
          gap: 12px;
          margin-bottom: 14px;
      }

      .reservation-stat-card {
          background: #fff;
          border: 1px solid #f1dfcb;
          border-radius: 10px;
          padding: 12px 14px;
      }

      .reservation-stat-label {
          color: #78716c;
          font-size: 12px;
          text-transform: uppercase;
          letter-spacing: .05em;
      }

      .reservation-stat-value {
          color: #7c2d12;
          font-size: 24px;
          font-weight: 700;
          margin-top: 4px;
      }

      .reservation-list-wrap {
          max-height: 380px;
          overflow: auto;
          border: 1px solid #f3e2cf;
          border-radius: 10px;
          background: #fff;
      }

      .reservation-item {
          padding: 12px 14px;
          border-bottom: 1px solid #f6ece2;
      }

      .reservation-item:last-child {
          border-bottom: 0;
      }

      .reservation-item-title {
          font-weight: 700;
          color: #1f2937;
      }

      .reservation-item-meta {
          font-size: 13px;
          color: #6b7280;
          margin-top: 3px;
      }
  </style>

  <script>
      (function($) {
          "use strict";

          var notificationUrl = "{{ route('admin.reservation-notifications') }}";
          var popupDataUrl = "{{ route('admin.reservation-popup-data') }}";
          var markViewedUrl = "{{ route('admin.reservation-mark-viewed') }}";
          var previousUnviewed = {{ (int) $unviewedReservationCount }};
          var audioContext = null;
          var hasInteracted = false;

          function esc(text) {
              return String(text || '')
                  .replace(/&/g, '&amp;')
                  .replace(/</g, '&lt;')
                  .replace(/>/g, '&gt;')
                  .replace(/"/g, '&quot;')
                  .replace(/'/g, '&#039;');
          }

          function getAudioContext() {
              if (!window.AudioContext && !window.webkitAudioContext) {
                  return null;
              }
              if (!audioContext) {
                  var Ctx = window.AudioContext || window.webkitAudioContext;
                  audioContext = new Ctx();
              }
              return audioContext;
          }

          function playReservationBeep() {
              if (!hasInteracted) {
                  return;
              }
              var ctx = getAudioContext();
              if (!ctx) {
                  return;
              }
              if (ctx.state === 'suspended') {
                  ctx.resume();
              }
              var now = ctx.currentTime;
              var osc = ctx.createOscillator();
              var gain = ctx.createGain();

              osc.type = 'sine';
              osc.frequency.setValueAtTime(880, now);
              gain.gain.setValueAtTime(0.0001, now);
              gain.gain.exponentialRampToValueAtTime(0.18, now + 0.03);
              gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.38);

              osc.connect(gain);
              gain.connect(ctx.destination);
              osc.start(now);
              osc.stop(now + 0.4);
          }

          function setBadge(count) {
              var $badge = $('#reservationCountBadge');
              if (count > 0) {
                  $badge.text(count > 99 ? '99+' : count).show();
              } else {
                  $badge.hide();
              }
              $('#reservationUnviewedCount').text(count);
          }

          function refreshNotifications() {
              $.ajax({
                  url: notificationUrl,
                  type: 'GET',
                  cache: false,
                  success: function(resp) {
                      var unviewed = parseInt(resp.unviewed_count || 0, 10);
                      var relevant = parseInt(resp.relevant_count || 0, 10);
                      $('#reservationRelevantCount').text(relevant);
                      setBadge(unviewed);

                      if (unviewed > previousUnviewed) {
                          var latest = (resp.items && resp.items.length) ? resp.items[0] : null;
                          var message = latest
                              ? 'New reservation: ' + latest.name + ' | ' + (latest.person_qty || '-') + ' people on ' + (latest.reserve_date || '-') + (latest.reserve_time ? ' at ' + latest.reserve_time : '')
                              : 'New reservation entry received.';
                          toastr.info(message);
                          playReservationBeep();
                      }

                      previousUnviewed = unviewed;
                  }
              });
          }

          function loadPopupReservations() {
              $.ajax({
                  url: popupDataUrl,
                  type: 'GET',
                  success: function(resp) {
                      var items = (resp && Array.isArray(resp.items)) ? resp.items : [];
                      var html = '';
                      if (!items.length) {
                          html = '<div class="text-center text-muted py-4">No reservations found for next 3 days / last 3 days.</div>';
                      } else {
                          items.forEach(function(item) {
                              html += '<div class="reservation-item">' +
                                  '<div class="reservation-item-title">' + esc(item.name) + '</div>' +
                                  '<div class="reservation-item-meta">' +
                                  'Date: ' + esc(item.reserve_date || '-') +
                                  (item.reserve_time ? ' | Time: ' + esc(item.reserve_time) : '') +
                                  (item.person_qty ? ' | Persons: ' + esc(item.person_qty) : '') +
                                  (item.phone ? ' | Phone: ' + esc(item.phone) : '') +
                                  '</div>' +
                                  '<div class="reservation-item-meta">Received: ' + esc(item.created_at_human || '-') + '</div>' +
                                  '</div>';
                          });
                      }
                      $('#reservationPopupList').html(html);
                  }
              });
          }

          function markViewed() {
              $.ajax({
                  url: markViewedUrl,
                  type: 'POST',
                  data: {
                      _token: '{{ csrf_token() }}'
                  },
                  complete: function() {
                      previousUnviewed = 0;
                      setBadge(0);
                  }
              });
          }

          function isFullscreenActive() {
              return !!(document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement);
          }

          function updateFullscreenIcon() {
              var $icon = $('#fullscreenToggleIcon');
              if (isFullscreenActive()) {
                  $icon.removeClass('fa-expand').addClass('fa-compress');
              } else {
                  $icon.removeClass('fa-compress').addClass('fa-expand');
              }
          }

          function requestFullscreen() {
              var docEl = document.documentElement;
              if (docEl.requestFullscreen) {
                  return docEl.requestFullscreen();
              }
              if (docEl.webkitRequestFullscreen) {
                  return docEl.webkitRequestFullscreen();
              }
              if (docEl.msRequestFullscreen) {
                  return docEl.msRequestFullscreen();
              }
              return Promise.reject();
          }

          function exitFullscreen() {
              if (document.exitFullscreen) {
                  return document.exitFullscreen();
              }
              if (document.webkitExitFullscreen) {
                  return document.webkitExitFullscreen();
              }
              if (document.msExitFullscreen) {
                  return document.msExitFullscreen();
              }
              return Promise.reject();
          }

          $(document).ready(function() {
              $(document).one('click keydown touchstart', function() {
                  hasInteracted = true;
                  var ctx = getAudioContext();
                  if (ctx && ctx.state === 'suspended') {
                      ctx.resume();
                  }
              });

              $('#reservationNotificationTrigger').on('click', function() {
                  $('#reservationNotificationModal').modal('show');
                  loadPopupReservations();
                  markViewed();
              });

              $('#fullscreenToggleBtn').on('click', function () {
                  if (isFullscreenActive()) {
                      exitFullscreen().catch(function () {});
                  } else {
                      requestFullscreen().catch(function () {
                          toastr.info('Fullscreen is not available in this browser.');
                      });
                  }
              });

              $(document).on('fullscreenchange webkitfullscreenchange msfullscreenchange', function () {
                  updateFullscreenIcon();
              });

              updateFullscreenIcon();

              setInterval(refreshNotifications, 15000);
          });
      })(jQuery);
  </script>

  @include('admin.footer')
