/**
 * Bluetooth Printing Utility
 * Handles connecting to Bluetooth thermal printers and sending print jobs
 */

class BluetoothPrinter {
    constructor() {
        this.device = null;
        this.server = null;
        this.service = null;
        this.characteristic = null;
        this.isConnected = false;
        this.isPrinting = false;
        this.queue = [];
        this.currentJob = null;
        this.connectionCallbacks = {
            onConnect: null,
            onDisconnect: null,
            onError: null
        };
    }

    /**
     * Initialize the Bluetooth printer
     * @param {Object} options - Printer options
     * @param {string} options.deviceName - Name of the Bluetooth device
     * @param {string} options.serviceUuid - Bluetooth service UUID
     * @param {string} options.characteristicUuid - Characteristic UUID for sending data
     * @returns {Promise<boolean>} - True if initialization is successful
     */
    async init(options = {}) {
        try {
            this.deviceName = options.deviceName || 'Thermal Printer';
            this.serviceUuid = options.serviceUuid || '0000ff00-0000-1000-8000-00805f9b34fb';
            this.characteristicUuid = options.characteristicUuid || '0000ff02-0000-1000-8000-00805f9b34fb';
            this.paperWidth = options.paperWidth || 32; // Default to 32mm width
            return true;
        } catch (error) {
            console.error('Failed to initialize Bluetooth printer:', error);
            throw error;
        }
    }

    /**
     * Connect to the Bluetooth printer
     * @returns {Promise<boolean>} - True if connection is successful
     */
    async connect() {
        try {
            if (this.isConnected) {
                console.log('Already connected to printer');
                return true;
            }

            console.log('Requesting Bluetooth device...');
            this.device = await navigator.bluetooth.requestDevice({
                filters: [{ name: this.deviceName }],
                optionalServices: [this.serviceUuid]
            });

            if (!this.device) {
                throw new Error('No device selected');
            }

            // Listen for disconnection
            this.device.addEventListener('gattserverdisconnected', this._handleDisconnect.bind(this));

            console.log('Connecting to GATT server...');
            this.server = await this.device.gatt.connect();

            console.log('Getting service...');
            this.service = await this.server.getPrimaryService(this.serviceUuid);

            console.log('Getting characteristic...');
            this.characteristic = await this.service.getCharacteristic(this.characteristicUuid);

            this.isConnected = true;
            console.log('Bluetooth printer connected');
            
            if (this.connectionCallbacks.onConnect) {
                this.connectionCallbacks.onConnect();
            }

            return true;
        } catch (error) {
            console.error('Failed to connect to printer:', error);
            this.isConnected = false;
            
            if (this.connectionCallbacks.onError) {
                this.connectionCallbacks.onError(error);
            }
            
            throw error;
        }
    }

    /**
     * Disconnect from the printer
     */
    disconnect() {
        if (this.device && this.device.gatt.connected) {
            this.device.gatt.disconnect();
        }
        this._reset();
    }

    /**
     * Print text
     * @param {string} text - Text to print
     * @param {Object} options - Print options
     * @param {boolean} options.bold - Bold text
     * @param {boolean} options.underline - Underline text
     * @param {string} options.align - Text alignment (left, center, right)
     * @param {number} options.size - Text size (1-8)
     * @returns {Promise<boolean>} - True if print job is successful
     */
    async printText(text, options = {}) {
        const defaultOptions = {
            bold: false,
            underline: false,
            align: 'left',
            size: 1,
            newLine: true
        };
        
        const printOptions = { ...defaultOptions, ...options };
        
        // Format the text with ESC/POS commands
        let commands = [];
        
        // Set text alignment
        switch (printOptions.align) {
            case 'center':
                commands.push(0x1B, 0x61, 0x01); // Center align
                break;
            case 'right':
                commands.push(0x1B, 0x61, 0x02); // Right align
                break;
            default: // left
                commands.push(0x1B, 0x61, 0x00); // Left align
        }
        
        // Set text size
        if (printOptions.size >= 1 && printOptions.size <= 8) {
            const width = Math.min(printOptions.size, 8);
            const height = Math.min(printOptions.size, 8);
            commands.push(0x1D, 0x21, (width - 1) | ((height - 1) << 4));
        }
        
        // Set bold
        if (printOptions.bold) {
            commands.push(0x1B, 0x45, 0x01); // Bold on
        }
        
        // Set underline
        if (printOptions.underline) {
            commands.push(0x1B, 0x2D, 0x01); // Underline on
        }
        
        // Add the text
        const encoder = new TextEncoder();
        const textData = encoder.encode(text);
        commands = commands.concat(Array.from(textData));
        
        // Reset formatting
        if (printOptions.bold) {
            commands.push(0x1B, 0x45, 0x00); // Bold off
        }
        
        if (printOptions.underline) {
            commands.push(0x1B, 0x2D, 0x00); // Underline off
        }
        
        // Reset text size
        if (printOptions.size > 1) {
            commands.push(0x1D, 0x21, 0x00); // Reset text size
        }
        
        // Add new line if needed
        if (printOptions.newLine) {
            commands.push(0x0A); // Line feed
        }
        
        return this._sendCommands(new Uint8Array(commands));
    }

    /**
     * Print a line of text
     * @param {string} text - Text to print
     * @param {Object} options - Print options
     * @returns {Promise<boolean>} - True if print job is successful
     */
    async printLine(text = '', options = {}) {
        return this.printText(text, { ...options, newLine: true });
    }

    /**
     * Print a divider line
     * @param {string} [char='-'] - Character to use for the divider
     * @returns {Promise<boolean>} - True if print job is successful
     */
    async printDivider(char = '-') {
        const line = char.repeat(this.paperWidth);
        return this.printLine(line, { align: 'center' });
    }

    /**
     * Print a blank line
     * @returns {Promise<boolean>} - True if print job is successful
     */
    async printBlankLine() {
        return this.printLine('');
    }

    /**
     * Feed paper
     * @param {number} [lines=1] - Number of lines to feed
     * @returns {Promise<boolean>} - True if command is successful
     */
    async feed(lines = 1) {
        const commands = [];
        for (let i = 0; i < lines; i++) {
            commands.push(0x0A); // Line feed
        }
        return this._sendCommands(new Uint8Array(commands));
    }

    /**
     * Cut paper
     * @param {boolean} [partial=false] - Partial cut (true) or full cut (false)
     * @returns {Promise<boolean>} - True if command is successful
     */
    async cut(partial = false) {
        const commands = [
            0x1D, 0x56, // GS V
            partial ? 0x41 : 0x00, // Partial or full cut
            0x00 // Feed before cut (0 = no feed)
        ];
        return this._sendCommands(new Uint8Array(commands));
    }

    /**
     * Print a ticket with header, content, and footer
     * @param {Object} ticket - Ticket data
     * @param {string} ticket.header - Header text
     * @param {string} ticket.content - Main content
     * @param {string} ticket.footer - Footer text
     * @returns {Promise<boolean>} - True if print job is successful
     */
    async printTicket(ticket) {
        try {
            this.isPrinting = true;
            
            // Initialize if not already connected
            if (!this.isConnected) {
                await this.connect();
            }
            
            // Print header if provided
            if (ticket.header) {
                await this.printLine(ticket.header, { align: 'center', size: 1 });
                await this.printBlankLine();
            }
            
            // Print main content
            if (ticket.content) {
                await this.printLine(ticket.content, { align: 'center', size: 2, bold: true });
                await this.printBlankLine();
            }
            
            // Print queue number if provided
            if (ticket.queueNumber) {
                await this.printLine(`Nomor Antrian`, { align: 'center', size: 1 });
                await this.printLine(ticket.queueNumber, { align: 'center', size: 3, bold: true });
                await this.printBlankLine();
            }
            
            // Print ticket details if provided
            if (ticket.details) {
                await this.printDivider('-');
                for (const [label, value] of Object.entries(ticket.details)) {
                    await this.printLine(`${label}: ${value}`, { align: 'left', size: 1 });
                }
                await this.printDivider('-');
            }
            
            // Print footer if provided
            if (ticket.footer) {
                await this.printBlankLine();
                await this.printLine(ticket.footer, { align: 'center', size: 1 });
            }
            
            // Feed and cut paper
            await this.feed(3);
            await this.cut();
            
            return true;
        } catch (error) {
            console.error('Failed to print ticket:', error);
            throw error;
        } finally {
            this.isPrinting = false;
        }
    }

    /**
     * Set connection event callbacks
     * @param {Object} callbacks - Callback functions
     * @param {Function} callbacks.onConnect - Called when connected
     * @param {Function} callbacks.onDisconnect - Called when disconnected
     * @param {Function} callbacks.onError - Called on error
     */
    on(callbacks) {
        if (callbacks.onConnect) {
            this.connectionCallbacks.onConnect = callbacks.onConnect;
        }
        if (callbacks.onDisconnect) {
            this.connectionCallbacks.onDisconnect = callbacks.onDisconnect;
        }
        if (callbacks.onError) {
            this.connectionCallbacks.onError = callbacks.onError;
        }
    }

    /**
     * Send raw commands to the printer
     * @private
     * @param {Uint8Array} data - Raw command data
     * @returns {Promise<boolean>} - True if command is sent successfully
     */
    async _sendCommands(data) {
        if (!this.isConnected || !this.characteristic) {
            throw new Error('Printer is not connected');
        }
        
        try {
            // Split large data into chunks to avoid MTU issues
            const CHUNK_SIZE = 512; // Typical BLE MTU is 20-512 bytes
            for (let i = 0; i < data.length; i += CHUNK_SIZE) {
                const chunk = data.slice(i, i + CHUNK_SIZE);
                await this.characteristic.writeValue(chunk);
                // Small delay between chunks to prevent buffer overflow
                await new Promise(resolve => setTimeout(resolve, 10));
            }
            return true;
        } catch (error) {
            console.error('Failed to send commands to printer:', error);
            throw error;
        }
    }

    /**
     * Handle Bluetooth device disconnection
     * @private
     */
    _handleDisconnect() {
        console.log('Bluetooth device disconnected');
        this._reset();
        
        if (this.connectionCallbacks.onDisconnect) {
            this.connectionCallbacks.onDisconnect();
        }
    }

    /**
     * Reset connection state
     * @private
     */
    _reset() {
        this.device = null;
        this.server = null;
        this.service = null;
        this.characteristic = null;
        this.isConnected = false;
        this.isPrinting = false;
    }
}

// Create a global instance
window.bluetoothPrinter = new BluetoothPrinter();

// Helper function to print a ticket using the global instance
window.printTicketWithBluetooth = async function(ticketData, printerSettings = {}) {
    try {
        // Initialize printer with settings
        await window.bluetoothPrinter.init({
            deviceName: printerSettings.printer_name || 'Thermal Printer',
            serviceUuid: printerSettings.service_uuid || '0000ff00-0000-1000-8000-00805f9b34fb',
            characteristicUuid: printerSettings.characteristic_uuid || '0000ff02-0000-1000-8000-00805f9b34fb',
            paperWidth: printerSettings.paper_width || 32
        });

        // Set up event handlers
        window.bluetoothPrinter.on({
            onConnect: () => {
                console.log('Connected to printer');
                document.dispatchEvent(new CustomEvent('bluetooth-printer-connected'));
            },
            onDisconnect: () => {
                console.log('Disconnected from printer');
                document.dispatchEvent(new CustomEvent('bluetooth-printer-disconnected'));
            },
            onError: (error) => {
                console.error('Printer error:', error);
                document.dispatchEvent(new CustomEvent('bluetooth-printer-error', { 
                    detail: { error: error.message || 'Unknown error occurred' }
                }));
            }
        });

        // Print the ticket
        await window.bluetoothPrinter.printTicket(ticketData);
        return { success: true };
        
    } catch (error) {
        console.error('Failed to print ticket:', error);
        throw error;
    }
};

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', () => {
    // Check if Web Bluetooth is supported
    if (!navigator.bluetooth) {
        console.warn('Web Bluetooth API is not supported in this browser');
        document.dispatchEvent(new CustomEvent('bluetooth-unsupported'));
    }
});
