<div class="enquiry-card" style="background: #fff; border: 1px solid #ede8df; border-radius: 4px; padding: 2rem; position: sticky; top: 2rem;">
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; font-weight: 600; color: #0f0f0f; margin-bottom: 0.5rem;">Enquire About This Property</h3>
    <p style="font-size: 0.8rem; color: #8c8070; margin-bottom: 1.5rem; font-weight: 300;">Interested? Send a message to the seller directly.</p>

    @if($showSuccess)
        <div style="background: #f4f9f4; border: 1px solid #d4e4d4; color: #2d5a2d; padding: 1.5rem; border-radius: 4px; text-align: center; animation: fadeIn 0.4s ease;">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 0.75rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p style="font-weight: 600; margin-bottom: 0.25rem;">Enquiry Sent Successfully!</p>
            <p style="font-size: 0.75rem;">The seller will be notified and will contact you shortly.</p>
            <button wire:click="$set('showSuccess', false)" style="margin-top: 1rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: #c9a96e; background: none; border: none; cursor: pointer;">Send another message</button>
        </div>
    @else
        <form wire:submit.prevent="submit" style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <input type="text" wire:model="name" placeholder="Full Name" 
                       style="width: 100%; padding: 0.65rem 0.875rem; border: 1px solid #ede8df; border-radius: 3px; font-size: 0.85rem; font-family: 'Outfit', sans-serif;">
                @error('name') <span style="color: #c0392b; font-size: 0.7rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <div style="flex: 1;">
                    <input type="email" wire:model="email" placeholder="Email Address" 
                           style="width: 100%; padding: 0.65rem 0.875rem; border: 1px solid #ede8df; border-radius: 3px; font-size: 0.85rem; font-family: 'Outfit', sans-serif;">
                    @error('email') <span style="color: #c0392b; font-size: 0.7rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
                <div style="flex: 1;">
                    <input type="text" wire:model="phone" placeholder="Phone Number" 
                           style="width: 100%; padding: 0.65rem 0.875rem; border: 1px solid #ede8df; border-radius: 3px; font-size: 0.85rem; font-family: 'Outfit', sans-serif;">
                    @error('phone') <span style="color: #c0392b; font-size: 0.7rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Smart Templates --}}
            <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.25rem;">
                <button type="button" wire:click="setTemplate('avail')" style="font-size: 0.65rem; padding: 0.35rem 0.65rem; border: 1px solid #ede8df; background: #fcfbf9; border-radius: 20px; color: #8c8070; transition: all 0.2s;" onmouseover="this.style.borderColor='#c9a96e';this.style.color='#0f0f0f'" onmouseout="this.style.borderColor='#ede8df';this.style.color='#8c8070'">Is it available?</button>
                <button type="button" wire:click="setTemplate('visit')" style="font-size: 0.65rem; padding: 0.35rem 0.65rem; border: 1px solid #ede8df; background: #fcfbf9; border-radius: 20px; color: #8c8070; transition: all 0.2s;" onmouseover="this.style.borderColor='#c9a96e';this.style.color='#0f0f0f'" onmouseout="this.style.borderColor='#ede8df';this.style.color='#8c8070'">Schedule visit</button>
                <button type="button" wire:click="setTemplate('price')" style="font-size: 0.65rem; padding: 0.35rem 0.65rem; border: 1px solid #ede8df; background: #fcfbf9; border-radius: 20px; color: #8c8070; transition: all 0.2s;" onmouseover="this.style.borderColor='#c9a96e';this.style.color='#0f0f0f'" onmouseout="this.style.borderColor='#ede8df';this.style.color='#8c8070'">Negotiate price</button>
            </div>

            <div>
                <textarea wire:model="message" rows="4" placeholder="Your Message..." 
                          style="width: 100%; padding: 0.65rem 0.875rem; border: 1px solid #ede8df; border-radius: 3px; font-size: 0.85rem; font-family: 'Outfit', sans-serif; resize: none;"></textarea>
                @error('message') <span style="color: #c0392b; font-size: 0.7rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled"
                    style="background: #0f0f0f; color: #fff; padding: 0.875rem; border: none; border-radius: 3px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: background 0.2s;"
                    onmouseover="this.style.background='#c9a96e';this.style.color='#0f0f0f'"
                    onmouseout="this.style.background='#0f0f0f';this.style.color='#fff'">
                
                <span wire:loading.remove>Send Enquiry Now</span>
                <span wire:loading>
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" style="display: inline;"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Processing...
                </span>
            </button>
            
            <p style="font-size: 0.65rem; color: #b0a8a0; text-align: center; margin-top: 0.5rem;">
                By clicking "Send Enquiry Now", you agree to our Terms of Use and Privacy Policy.
            </p>
        </form>
    @endif
</div>
