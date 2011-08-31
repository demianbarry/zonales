package org.zonales.parser.parser;

/* -----------------------------------------------------------------------------
 * ParserException.java
 * -----------------------------------------------------------------------------
 *
 * Producer : com.parse2.aparse.Parser 2.0
 * Produced : Wed Jul 20 09:15:24 ART 2011
 *
 * -----------------------------------------------------------------------------
 */
import org.zonales.errors.ZMessage;

/**
 * <p>Signals that a parse failure has occurred.</p>
 * 
 * <p>Producer : com.parse2.aparse.Parser 2.0<br/>
 * Produced : Wed Jul 20 09:15:24 ART 2011</p>
 */
public class ValidationException extends Exception {

    private ZMessage reason;

    /**
     * Creates a parser exception from the specified parse failure information.
     *
     * @param reason A description of the parse failure.
     * @param text The string of characters being parsed.
     * @param index The index to the character at which the parse failure occurred.
     * @param ruleStack The ABNF rule stack at the point the parse failure occurred.
     */
    public ValidationException(ZMessage reason) {
        this.reason = reason;
    }

    /**
     * Returns the description of the parse failure.
     *
     * @return The description of the parse failure.
     */
    public ZMessage getReason() {
        return reason;
    }
}

/* -----------------------------------------------------------------------------
 * eof
 * -----------------------------------------------------------------------------
 */
