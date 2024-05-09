import { vi } from 'vitest';

declare global {
    var jest: typeof vi | undefined;
}
