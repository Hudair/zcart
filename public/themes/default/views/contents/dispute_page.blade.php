<section>
  <div class="container">
    <div class="row">
      <div class="col-md-4 bg-light">
        <p class="section-title lead space20">{!! trans('theme.section_headings.how_to_open_a_dispute') !!}</p>
        <h4 class="mb-2">{!! trans('theme.help.first_step') !!}:</h4>
        <p class="mb-4">{!! trans('theme.help.how_to_open_a_dispute_first_step') !!}</p>

        <h4 class="mb-2">{!! trans('theme.help.second_step') !!}:</h4>
        <p class="mb-4">{!! trans('theme.help.how_to_open_a_dispute_second_step') !!}</p>

        <h4 class="mb-2">{!! trans('theme.help.third_step') !!}:</h4>
        <p class="mb-4">{!! trans('theme.help.how_to_open_a_dispute_third_step') !!}</p>
      </div>

      <div class="col-md-8">
        @php
          $progress = $order->dispute ? $order->dispute->progress() : 0;
        @endphp
        <div class="step-wizard-wrapper">
          <div class="step-wizard">
              <div class="progress">
                <div class="progressbar empty"></div>
                <div id="prog" class="progressbar" style=""></div>
                <div id="prog" class="progressbar" style="width: {{$progress}}%;"></div>
              </div>
              <ul>
                <li class="{{ $progress > 33 ? 'done' : 'active' }}">
                  <a id="step1">
                    <span class="step">1</span>
                    <span class="title">{!! trans('theme.open_a_dispute') !!}</span>
                  </a>
                </li>
                <li class="
                  @if($progress > 66)
                    done
                  @elseif($progress > 33)
                    active
                  @endif
                ">
                  <a id="step2">
                    <span class="step">2</span>
                    <span class="title">{!! trans('theme.seller_helps_you') !!}</span>
                  </a>
                </li>
                <li class="
                  @if($progress == 100)
                    done
                  @elseif($progress > 66)
                    active
                  @endif
                ">
                  <a id="step3">
                      <span class="step">3</span>
                      <span class="title">{!! trans('theme.marketplace_steps_in', ['marketplace' => get_platform_title()]) !!}<br/>
                          <i class="small hidden-xs">{!! trans('theme.help.when_marketplace_steps_in') !!}</i>
                      </span>
                  </a>
                </li>
                <li class="{{ $progress == 100 ? 'done' : '' }}">
                  <a id="step4">
                    <span class="step">4</span>
                    <span class="title">{!! trans('theme.dispute_finished') !!}</span>
                  </a>
                </li>
              </ul>
          </div>
        </div><!-- /.step-wizard-wrapper -->

        <div class="space20"></div>

        @if($order->dispute)
          <table class="table" id="buyer-order-table">
              <thead>
                  <tr><th colspan="3">{!! trans('theme.dispute_detail') !!}</th></tr>
              </thead>
              <tbody>
                  <tr class="order-info-head">
                      <td width="50%">
                        <h5 class="mb-2">
                          <span>{!! trans('theme.store') !!}:</span>
                          @if($order->shop->slug)
                            <a href="{{ route('show.store', $order->shop->slug) }}"> {{ $order->shop->name }}</a>
                          @else
                            {!! trans('theme.seller') !!}
                          @endif
                        </h5>
                        <h5>
                            <span>{!! trans('theme.status') !!}</span>
                            {!! $order->dispute->statusName() !!}
                        </h5>
                      </td>
                      <td width="25%" class="order-amount">
                        <h5 class="mb-2">
                          <span>{!! trans('theme.refund_amount') !!}: </span>
                          {{ get_formated_currency($order->dispute->refund_amount, true, 2) }}
                        </h5>
                        <h5>
                          <span>{!! trans('theme.return_goods') !!}:</span>
                          {{ $order->dispute->return_goods == 1 ? trans('theme.yes') : trans('theme.no') }}
                        </h5>
                      </td>
                      <td width="25%" class="store-info">
                        <h5 class="mb-2">
                          <span>{!! trans('theme.order_id') !!}: </span>
                          <a href="{{ route('order.detail', $order) }}">{{ $order->order_number }}</a>
                        </h5>
                        <h5>
                          <span>{!! trans('theme.order_received') !!}:</span>
                          {{ $order->dispute->order_received == 1 ? trans('theme.yes') : trans('theme.no') }}
                        </h5>
                      </td>
                  </tr> <!-- /.order-info-head -->
                  <tr class="order-body">
                    <td colspan="3">
                      <p class="lead">
                        <span>{!! trans('theme.reason') !!}:
                        </span>{{ $order->dispute->dispute_type->detail }}
                      </p>

                      @if($order->dispute->description)
                        <div>
                          {!! $order->dispute->description !!}
                          @if(count($order->dispute->attachments))
                            <small class="pull-right">
                              {{ trans('app.attachments') . ': ' }}
                              @foreach($order->dispute->attachments as $attachment)
                                <a href="{{ route('attachment.download', $attachment->path) }}"><i class="fas fa-file"></i></a>
                              @endforeach
                            </small>
                          @endif
                        </div>
                      @endif

                      <div class="space50"></div>

                      @if($order->dispute->replies->count() > 0)
                        @foreach($order->dispute->replies as $reply)
                          <div class="row">
                            <div class="col-md-2 nopadding-right no-print">
                              @if($reply->user_id)
                                @if($reply->user->image)
                                  <img src="{{ get_storage_file_url(optional($reply->user->image)->path, 'thumbnail') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
                                @else
                                  <img src="{{ get_gravatar_url($reply->user->email, 'thumbnail') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
                                @endif

                                {{ $reply->user->getName() }}
                              @endif
                            </div>

                            <div class="col-md-8 nopadding">
                              <blockquote style="font-size: 1em;" class="{{ $reply->customer_id ? 'blockquote-reverse' : ''}}">
                                {!! $reply->reply !!}

                                @if(count($reply->attachments))
                                  <small class="no-print">
                                    {{ trans('app.attachments') . ': ' }}
                                    @foreach($reply->attachments as $attachment)
                                      <a href="{{ route('attachment.download', $attachment) }}"><i class="fas fa-file"></i></a>
                                    @endforeach
                                  </small>
                                @endif

                                <footer>{{ $reply->updated_at->diffForHumans() }}</footer>
                              </blockquote>
                            </div>

                            <div class="col-md-2 nopadding-left no-print">
                              @if($reply->customer_id)
                                @if($reply->customer->image)
                                  <img src="{{ get_storage_file_url(optional($reply->customer->image)->path, 'thumbnail') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
                                @else
                                  <img src="{{ get_gravatar_url($reply->customer->email, 'thumbnail') }}" class="img-circle img-sm" alt="{{ trans('app.avatar') }}">
                                @endif

                                {{ $reply->customer->getName() }}
                              @endif
                            </div>
                          </div>
                        @endforeach
                      @endif

                      <div class="space20"></div>

                      <div class="text-center space20">
                        @if($order->dispute->isClosed())
                          <a class="btn btn-danger flat" href="javascript:void(0)" data-toggle="modal" data-target="#disputeAppealModal">{!! trans('theme.button.appeal') !!}</a>
                        @else
                          <a class="btn btn-info flat" href="javascript:void(0)" data-toggle="modal" data-target="#disputeResponseModal">{!! trans('theme.button.response') !!}</a>

                          {!! Form::open(['route' => ['dispute.markAsSolved', $order->dispute], 'class' => 'form-btn']) !!}
                              {!! Form::button(trans('theme.mark_as_solved'), ['type' => 'submit', 'class' => 'confirm btn btn-primary flat']) !!}
                          {!! Form::close() !!}
                        @endif
                      </div>
                    </td>
                  </tr> <!-- /.order-body -->
              </tbody>
          </table>
        @else
          <p class="text-center">
              <a href="{{ route('order.detail', $order) . '#message-section' }}" class="btn btn-primary flat">{!! trans('theme.button.contact_seller') !!}</a>

              @unless($order->dispute)
                <a href="javascript:void(0);" data-toggle="modal" data-target="#disputeOpenModal" class="btn btn-black flat">{!! trans('theme.button.open_dispute') !!}</a>
              @endunless
          </p>

          <div class="sep my-5"></div>

          <h4 class="pb-2">{!! trans('theme.button.refund_request') !!}:</h4>
          <p class="text-muted pb-4"> {!! trans('theme.help.reason_to_refund_request') !!}</p>

          <h4 class="pb-2">{!! trans('theme.button.return_goods') !!}:</h4>
          <p class="text-muted">{!! trans('theme.help.reason_to_return_goods') !!}</p>
        @endif
      </div><!-- /.col-md-8 -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>
<div class="clearfix space50"></div>